<?php

namespace Plentymarket\Services;

use Plenty\Modules\Blog\Contracts\BlogPostRepositoryContract;
use Plenty\Modules\Blog\Models\BlogPost;

/**
 * Class BlogService
 * @package Plentymarket\Services
 */
class BlogService
{
	/**
	 * @var BlogPostRepositoryContract
	 */
	private $blogPostRepositoryContract;

	/**
	 * BlogService constructor.
	 * @param BlogPostRepositoryContract $blogPostRepositoryContract
	 */
	function __construct (BlogPostRepositoryContract $blogPostRepositoryContract)
	{
		$this->blogPostRepositoryContract = $blogPostRepositoryContract;
	}

	/**
	 * 获取文档列表
	 * @param int $page
	 * @param int $itemsPerPage
	 * @return array
	 */
	function getAll (int $page = 1, int $itemsPerPage = 50)
	{
		return $this->blogPostRepositoryContract->listPosts($page, $itemsPerPage);
	}

	/**
	 * @param $id
	 * @return BlogPost
	 */
	function get ($id): BlogPost
	{
		return $this->blogPostRepositoryContract->getPost($id);
	}

	/**
	 * 计算相似度
	 * @param $keyword
	 * @param $data
	 * @return int
	 */
	private function like ($keyword, $data)
	{
		if (empty($keyword)) {
			return 1;
		}
		$num = 0;
		foreach ($data as $value) {
			if (stripos($value, $keyword) !== false) {
				$num++;
			}
		}
		return $num;
	}

	private function isLike ($method, $item, $param)
	{
		switch ($method) {
			case 'keyword':
				return $this->like($param, [
					$item['data']['metaData']['title'],
					$item['data']['metaData']['description'],
					$item['data']['post']['shortDescription'],
					$item['data']['post']['body'],
					$item['data']['post']['title'],
				]);
				break;
			case 'category_id':
				return $item['data']['category']['id'] == $param;
				break;
			case 'tag':
				break;
			case 'title_equal':
				return $item['data']['post']['title'] == $param;
		}
	}

	function getTitle ($title)
	{
		$data = [];
		$inner_page = 1;
		do {
			$result = $this->getAll($inner_page);

			foreach ($result['entries'] as $item) {
				if ($this->isLike('title_equal', $item, $title)) {
					return $item;
				}
			}

			$inner_page++;
		} while (!$result['isLastPage']);
		return null;
	}

	/**
	 * 根据分类ID搜索
	 * @param int $category_id
	 * @param int $page
	 * @param int $itemsPerPage
	 * @return array
	 */
	function category_id (int $category_id, $page = 1, $itemsPerPage = 50)
	{
		$data = [];
		$inner_page = 1;
		do {
			$result = $this->getAll($inner_page);

			foreach ($result['entries'] as $item) {
				if ($this->isLike('category_id', $item, $category_id)) {
					$data[] = $item;
				}
			}

			$inner_page++;
		} while (!$result['isLastPage']);

		//取最前面的匹配度的
		return array_slice($data, ($page - 1) * $itemsPerPage, $itemsPerPage);
	}

	/**
	 * 根据关键字搜索
	 * @param $keyword
	 * @param int $page
	 * @param int $itemsPerPage
	 * @return array
	 */
	function search ($keyword, $page = 1, $itemsPerPage = 50)
	{
		$data = [];
		$inner_page = 1;
		do {
			$result = $this->getAll($inner_page);

			foreach ($result['entries'] as $item) {
				$likes = $this->isLike('keyword', $item, $keyword);
				if ($likes) {
					$data[] = [
						'likes' => $likes,
						'item' => $item
					];
				}
			}

			$inner_page++;
		} while (!$result['isLastPage']);

		//按照相似度排序  从大到小
		if (!empty($keyword)) {
			usort($data, function ($a, $b) {
				return $a['likes'] < $b['likes'] ? 1 : -1;
			});
		}

		//取最前面的匹配度的
		return array_slice(array_column($data, 'item'), ($page - 1) * $itemsPerPage, $itemsPerPage);
	}
}
