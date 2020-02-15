<?php

namespace Plentymarket\Services;

use Plenty\Modules\StockManagement\Stock\Contracts\StockRepositoryContract;

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
	 * @param array $columns
	 * @param int $page
	 * @param int $itemsPerPage
	 * @return \Plenty\Repositories\Models\PaginatedResult
	 */
	function listStock (array $columns = [], int $page = 1, int $itemsPerPage = 50)
	{
		return $this->stockRepositoryContract->listStock($columns, $page, $itemsPerPage);
	}
}
