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
	 * 获取所有的文档列表
	 * @return array
	 */
	function getAll ()
	{
		return $this->blogPostRepositoryContract->listPosts();
	}
}
