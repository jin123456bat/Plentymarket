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
		$this->warehouseRepositoryContract = $warehouseRepositoryContract;
	}

	/**
	 * 获取所有仓库列表
	 * @return array
	 */
	function getAll ()
	{
		return $this->warehouseRepositoryContract->all();
	}
}
