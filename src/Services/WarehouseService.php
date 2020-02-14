<?php

namespace Plentymarket\Services;

use Plenty\Modules\StockManagement\Warehouse\Contracts\WarehouseRepositoryContract;

/**
 * Class WarehouseService
 * @package Plentymarket\Services
 */
class WarehouseService
{
	/**
	 * @var WarehouseRepositoryContract
	 */
	private $warehouseRepositoryContract;

	/**
	 * WarehouseService constructor.
	 * @param WarehouseRepositoryContract $warehouseRepositoryContract
	 */
	function __construct (WarehouseRepositoryContract $warehouseRepositoryContract)
	{
		$this->$warehouseRepositoryContract = $warehouseRepositoryContract;
	}

	/**
	 * @return mixed
	 */
	function getAll ()
	{
		return $this->warehouseRepositoryContract->all();
	}
}
