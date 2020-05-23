<?php

namespace Plentymarket\Services;

use Plenty\Modules\Category\Contracts\CategoryRepositoryContract;
use Plenty\Modules\Category\Models\Category;
use Plenty\Repositories\Models\PaginatedResult;
use Plentymarket\Helper\Utils;
use Plentymarket\Services\UrlBuilder\UrlQuery;

/**
 * Class CategoryService
 * @package Plentymarket\Services
 */
class CategoryService
{
	/**
	 * @var CategoryRepositoryContract
	 */
	private $categoryRepositoryContract;

	/**
	 * copy
	 * @var Category
	 */
	private $currentCategory = null;

	/**
	 * copy
	 * @var array
	 */
	private $currentCategoryTree = [];

	/**
	 * copy
	 * @var array
	 */
	private $currentItem = [];

	/**
	 * CategoryService constructor.
	 * @param CategoryRepositoryContract $categoryRepositoryContract
	 */
	function __construct (CategoryRepositoryContract $categoryRepositoryContract)
	{
		$this->categoryRepositoryContract = $categoryRepositoryContract;
	}

	/**
	 * 获取所有分类
	 * @return PaginatedResult
	 */
	function getAll ()
	{
		return $this->categoryRepositoryContract->search(null, 0, 99999, [], []);
	}

	/**
	 * 获取一个分类信息
	 * @param int $catID The category ID
	 * @param string $lang The language to get the category
	 * @return Category
	 */
	public function get ($catID, $lang = null)
	{
		if ($lang === null) {
			$lang = Utils::getLang();
		}
		return $this->categoryRepositoryContract->get($catID, $lang);;
	}

	/**
	 * Return the URL for a given category ID.
	 * @param Category $category the category to get the URL for
	 * @param string $lang the language to get the URL for
	 * @param int |null $webstoreId
	 * @return string|null
	 */
	public function getURL (Category $category, $lang = null, $webstoreId = null)
	{
		if (empty($lang)) {
			$lang = Utils::getLang();
		}

		if (is_null($webstoreId)) {
			$webstoreId = Utils::getWebstoreId();
		}

		if ($category->details->first() === null) {
			return null;
		}

		$categoryURL = pluginApp(
			UrlQuery::class,
			['path' => $this->categoryRepositoryContract->getUrl($category->id, $lang, false, $webstoreId), 'lang' => $lang]
		);
		return $categoryURL->toRelativeUrl($lang !== $lang);
	}

	/**
	 * copy
	 * Set the current category by ID.
	 * @param Category $cat The current category
	 */
	public function setCurrentCategory ($cat)
	{
		$lang = Utils::getLang();
		$this->currentCategory = null;
		$this->currentCategoryTree = [];

		if ($cat === null) {
			return;
		}

		// List parent/open categories
		$this->currentCategory = $cat;
		while ($cat !== null) {
			$this->currentCategoryTree[$cat->level] = $cat;
			$cat = $this->categoryRepositoryContract->get($cat->parentCategoryId, $lang, Utils::getWebstoreId());
		}
	}

	/**
	 * copy
	 * @param $item
	 */
	public function setCurrentItem ($item)
	{
		$this->currentItem = $item;
	}

	/**
	 * 获取树状分类图
	 * @param string $type
	 * @return array
	 */
	function getTree (string $type = 'item')
	{
		$categoryTree = [];
		$category_list = $this->getAll()->getResult();
		foreach ($category_list as $value) {
			if (empty($value['parentCategoryId']) && $value['type'] == $type) {
				$value['children'] = $this->getSubCategory($category_list, $value['id'], $type);
				$categoryTree[] = $value;
			}
		}
		return $categoryTree;
	}

	/**
	 * 获取分类的子分类
	 * @param $category_list
	 * @param int|null $category_id
	 * @param string $type
	 * @return array
	 */
	function getSubCategory ($category_list, int $category_id = null, $type = 'item')
	{
		$temp_category_list = [];
		foreach ($category_list as $value) {
			if ($value['parentCategoryId'] == $category_id && $value['type'] == $type) {
				$value['children'] = $this->getSubCategory($category_list, $value['id'], $type);
				$temp_category_list[] = $value;
			}
		}
		return $temp_category_list;
	}

}
