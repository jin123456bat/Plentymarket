<?php

namespace Plentymarket\Services;

use Plenty\Modules\Item\Item\Contracts\ItemRepositoryContract;
use Plenty\Modules\Item\ItemProperty\Contracts\ItemPropertyRepositoryContract;

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
	 * @var ItemPropertyRepositoryContract
	 */
	private $itemPropertyRepositoryContract;

	/**
	 * ItemService constructor.
	 * @param ItemRepositoryContract $itemRepositoryContract
	 * @param ItemPropertyRepositoryContract $itemPropertyRepositoryContract
	 */
	function __construct (ItemRepositoryContract $itemRepositoryContract, ItemPropertyRepositoryContract $itemPropertyRepositoryContract)
	{
		$this->itemRepositoryContract = $itemRepositoryContract;
		$this->itemPropertyRepositoryContract = $itemPropertyRepositoryContract;
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
		return $this->itemRepositoryContract->search([], $lang, $page, $itemsPerPage, $with);
	}
}
