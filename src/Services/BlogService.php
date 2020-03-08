<?php

namespace Plentymarket\Services;

use Plenty\Modules\Blog\Contracts\BlogPostRepositoryContract;

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
}
