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
}
