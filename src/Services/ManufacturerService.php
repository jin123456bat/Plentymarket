<?php

namespace Plentymarket\Services;

use Plenty\Modules\Item\Manufacturer\Contracts\ManufacturerRepositoryContract;
use Plenty\Repositories\Models\PaginatedResult;

/**
 * 制造商
 * Class ManufacturerService
 * @package Plentymarket\Services
 */
class ManufacturerService
{
	/**
	 * @var ManufacturerRepositoryContract
	 */
	private $manufacturerRepositoryContract;

	/**
	 * ManufacturerService constructor.
	 * @param ManufacturerRepositoryContract $manufacturerRepositoryContract
	 */
	function __construct (ManufacturerRepositoryContract $manufacturerRepositoryContract)
	{
		$this->$manufacturerRepositoryContract = $manufacturerRepositoryContract;
	}

	/**
	 * @param int $perPage
	 * @param int $page
	 * @param array $with
	 * @return PaginatedResult
	 */
	function getAll (int $perPage = 50, int $page = 1, array $with = []): PaginatedResult
	{
		return $this->manufacturerRepositoryContract->all([], $perPage, $page, $with);
	}
}
