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
	 * @param array $with
	 * @return array
	 */
	function getAll ($with = ['repairWarehouse'])
	{
		return $this->warehouseRepositoryContract->all($with, []);
	}
}
