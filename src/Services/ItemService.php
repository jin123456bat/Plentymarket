<?php

namespace Plentymarket\Services;

use Plenty\Modules\Item\Item\Contracts\ItemRepositoryContract;

/**
 * Class ItemService
 * @package Plentymarket\Services
 */
class ItemService
{
	/**
	 * @var ItemRepositoryContract
	 */
	private $itemRepositoryContract;

	/**
	 * ItemService constructor.
	 * @param ItemRepositoryContract $itemRepositoryContract
	 */
	function __construct (ItemRepositoryContract $itemRepositoryContract)
	{
		$this->itemRepositoryContract = $itemRepositoryContract;
	}

	/**
	 * @param int $page
	 * @param int $itemsPerPage
	 * @param array $with
	 * @param array $lang
	 * @return mixed
	 */
	function getAll (int $page = 1, int $itemsPerPage = 50, array $with = [], $lang = [])
	{
		return $this->itemRepositoryContract->search($lang, $page, $itemsPerPage, $with);
	}
}
