<?php

namespace Plentymarket\Services;

use Plenty\Plugin\Http\Request;

/**
 * Class HomeService
 * @package Plentymarket\Services
 */
class HomeService
{
	/**
	 * @var ConfigService
	 */
	private $configService;

	/**
	 * @var ItemListService
	 */
	private $itemListService;

	/**
	 * @var BlogService
	 */
	private $blogService;

	/**
	 * @var Request
	 */
	private $request;

	/**
	 * @var CategoryService
	 */
	private $categoryService;

	/**
	 * HomeService constructor.
	 * @param ConfigService $configService
	 * @param ItemListService $itemListService
	 * @param BlogService $blogService
	 * @param CategoryService $categoryService
	 * @param Request $request
	 */
	function __construct (ConfigService $configService, ItemListService $itemListService, BlogService $blogService, CategoryService $categoryService, Request $request)
	{
		$this->configService = $configService;
		$this->itemListService = $itemListService;
		$this->blogService = $blogService;
		$this->request = $request;
		$this->categoryService = $categoryService;
	}

	/**
	 * @return array
	 */
	function product_new (): array
	{
//		$home_product_new_string = $this->configService->getTemplateConfig('basic.home_product_new');
		$home_product_new_string = '139,147,153,155-184';
		$home_product_new_array = explode(',', $home_product_new_string);
		$data = [];
		foreach ($home_product_new_array as $value) {
			if (strpos($value, '-')) {
				list($start, $end) = explode('-', $value, 2);
				$data = array_merge($data, range($start, $end));
			} else {
				$data[] = (int)$value;
			}
		}
		return $this->itemListService->getItems(array_filter(array_unique($data)));
	}

	/**
	 * @return array
	 */
	function product_top (): array
	{
//		$home_product_top_string = $this->configService->getTemplateConfig('basic.home_product_top');
		$home_product_top_string = '139,147,153,155-184';
		$home_product_top_array = explode(',', $home_product_top_string);
		$data = [];
		foreach ($home_product_top_array as $value) {
			if (strpos($value, '-')) {
				list($start, $end) = explode('-', $value, 2);
				$data = array_merge($data, range($start, $end));
			} else {
				$data[] = (int)$value;
			}
		}
		return $this->itemListService->getItems(array_filter(array_unique($data)));
	}

	/**
	 * @return array
	 */
	function product_popular (): array
	{
//		$home_product_popular_string = $this->configService->getTemplateConfig('basic.home_product_popular');
		$home_product_popular_string = '139,147,153,155-184';
		$home_product_popular_array = explode(',', $home_product_popular_string);
		$data = [];
		foreach ($home_product_popular_array as $value) {
			if (strpos($value, '-')) {
				list($start, $end) = explode('-', $value, 2);
				$data = array_merge($data, range($start, $end));
			} else {
				$data[] = (int)$value;
			}
		}
		return $this->itemListService->getItems(array_filter(array_unique($data)));
	}

	/**
	 * @return array
	 */
	function product_deals (): array
	{
//		$home_product_deals_string = $this->configService->getTemplateConfig('basic.home_product_deals');
		$home_product_deals_string = '139:2020-07-08,147:2020-07-08,153:2020-07-08';
		$home_product_deals_array = explode(',', $home_product_deals_string);
		$data = [];
		foreach ($home_product_deals_array as $value) {
			list($itemId, $endtime) = explode(':', $value, 2);
			$data[$itemId] = $endtime;
		}
		$home_product_deals = $this->itemListService->getItems(array_keys($data));
		$home_product_deals_list = $home_product_deals['list'];
		foreach ($home_product_deals_list as &$item) {
			$item['countdown'] = date('Y/m/d', strtotime($data[$item['id']] ?? date('Y/m/d')));
		}
		$home_product_deals['list'] = $home_product_deals_list;
		return $home_product_deals;
	}

	/**
	 * @return array
	 */
	function product_category ()
	{
		$categoryList = [];
		for ($i = 1; $i <= 4; $i++) {
			$category = $this->categoryService->get($this->configService->getTemplateConfig('basic.home_category_' . $i))->toArray();
			$category['details'][0]['imagePath'] = 'https://' . $this->request->getHttpHost() . '/documents/' . $category['details'][0]['imagePath'];
			$categoryList['home_category_' . $i] = $category;
		}
		return $categoryList;
	}

	/**
	 * @return array
	 */
	function article (): array
	{
		//最底部文章
		$home_category_blog = $this->configService->getTemplateConfig('basic.home_category_blog');
		if (!empty($home_category_blog)) {
			$home_category_blog_list = $this->blogService->category_id($home_category_blog);
			foreach ($home_category_blog_list as $key => $r) {
				$home_category_blog_list[$key]['data']['images'] = array_filter(array_map(function ($value) {
					return $value['path'];
				}, $r['data']['images']));
			}
			return $home_category_blog_list;
		}
		return [];
	}

}
