<?php

namespace Plentymarket\Services;

use Plenty\Modules\Order\Shipping\Countries\Contracts\CountryRepositoryContract;
use Plenty\Modules\Order\Shipping\Countries\Models\Country;

/**
 * Class CountryService
 * @package Plentymarket\Services
 */
class CountryService
{
	/**
	 * @var CountryRepositoryContract
	 */
	private $countryRepositoryContract;

	/**
	 * CountryService constructor.
	 * @param CountryRepositoryContract $countryRepositoryContract
	 */
	function __construct (CountryRepositoryContract $countryRepositoryContract)
	{
		$this->countryRepositoryContract = $countryRepositoryContract;
	}

	/**
	 * 获取所有的可用的国家列表
	 * @return array
	 */
	function getAll (): array
	{
		return $this->countryRepositoryContract->getActiveCountriesList()->toArray();
	}

	/**
	 * 获取国家的详细信息
	 * @param $country_id
	 * @return Country
	 */
	function getModel ($country_id)
	{
		return $this->countryRepositoryContract->getCountryById($country_id);
	}
}
