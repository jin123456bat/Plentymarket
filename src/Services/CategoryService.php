<?php

namespace Plentymarket\Services;

use Plenty\Modules\Category\Contracts\CategoryRepositoryContract;
use Plentymarket\Helper\RouteConfig;

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
	 * CategoryService constructor.
	 * @param CategoryRepositoryContract $categoryRepositoryContract
	 */
	function __construct (CategoryRepositoryContract $categoryRepositoryContract)
	{
		$this->categoryRepositoryContract = $categoryRepositoryContract;
	}

	/**
	 * @return \Plenty\Modules\Category\Models\Category
	 */
	function getAll ()
	{
		return $this->categoryRepositoryContract->search();
	}

}
