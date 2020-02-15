<?php

namespace Plentymarket\Services;

use Plenty\Modules\StockManagement\Stock\Contracts\StockRepositoryContract;
use Plenty\Repositories\Models\PaginatedResult;

/**
 * Class StockService
 * @package Plentymarket\Services
 */
class StockService
{
	/**
	 * @var StockRepositoryContract
	 */
	private $stockRepositoryContract;

	/**
	 * StockService constructor.
	 * @param StockRepositoryContract $stockRepositoryContract
	 */
	function __construct (StockRepositoryContract $stockRepositoryContract)
	{
		$this->stockRepositoryContract = $stockRepositoryContract;
	}

	/**
	 * 获取所有库存
	 * @param array $columns
	 * @param int $page
	 * @param int $itemsPerPage
	 * @return PaginatedResult
	 */
	function listStock (int $page = 1, int $itemsPerPage = 50)
	{
		return $this->stockRepositoryContract->listStock([], $page, $itemsPerPage);
	}

	/**
	 * 根据仓库ID获取仓库库存
	 * @param int $warehouseId
	 * @param int $page
	 * @param int $itemsPerPage
	 * @return PaginatedResult
	 */
	function listStockByWarehouse (int $warehouseId, int $page = 1, int $itemsPerPage = 50): PaginatedResult
	{
		return $this->stockRepositoryContract->listStockByWarehouseId([], $page, $itemsPerPage);
	}
}
