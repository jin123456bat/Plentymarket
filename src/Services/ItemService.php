<?php

namespace Plentymarket\Services;

use Plenty\Modules\Item\Item\Contracts\ItemRepositoryContract;
use Plenty\Modules\Item\ItemImage\Contracts\ItemImageRepositoryContract;
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
	 * @var ItemImageRepositoryContract
	 */
	private $itemImageRepositoryContract;

	/**
	 * ItemService constructor.
	 * @param ItemRepositoryContract $itemRepositoryContract
	 * @param ItemPropertyRepositoryContract $itemPropertyRepositoryContract
	 * @param ItemImageRepositoryContract $itemImageRepositoryContract
	 */
	function __construct (ItemRepositoryContract $itemRepositoryContract, ItemPropertyRepositoryContract $itemPropertyRepositoryContract, ItemImageRepositoryContract $itemImageRepositoryContract)
	{
		$this->itemRepositoryContract = $itemRepositoryContract;
		$this->itemPropertyRepositoryContract = $itemPropertyRepositoryContract;
		$this->itemImageRepositoryContract = $itemImageRepositoryContract;
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
		$item_list = $this->itemRepositoryContract->search([], $lang, $page, $itemsPerPage, $with);
		$item_list = $item_list->getResult();
		foreach ($item_list as &$item) {
			$item['images'] = $this->itemImageRepositoryContract->findByItemId($item['id']);
		}
		return $item_list;
	}
}
