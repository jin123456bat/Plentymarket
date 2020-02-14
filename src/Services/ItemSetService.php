<?php

namespace Plentymarket\Services;

use Plenty\Modules\ItemSet\Contracts\ItemSetRepositoryContract;

class ItemSetService
{
	private $itemSetRepositoryContract;

	function __construct (ItemSetRepositoryContract $itemSetRepositoryContract)
	{
		$this->itemSetRepositoryContract = $itemSetRepositoryContract;
	}

	function getAll ()
	{
		return $this->itemSetRepositoryContract->all();
	}

}
