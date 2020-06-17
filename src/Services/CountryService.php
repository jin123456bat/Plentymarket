<?php

namespace Plentymarket\Services;

use Plenty\Modules\Order\Shipping\Countries\Contracts\CountryRepositoryContract;
use Plenty\Modules\Order\Shipping\Countries\Models\Country;
use Plentymarket\Helper\Utils;

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
	 */
	function getAll (): array
	{
		$countryList = $this->countryRepositoryContract->getActiveCountriesList();
		return json_decode(json_encode($countryList), true);
	}

	function activateCountry (int $countryId): Country
	{
		return $this->countryRepositoryContract->activateCountry($countryId);
	}

	function getCountriesList (int $active, array $with): array
	{
		$countryList = $this->countryRepositoryContract->getCountriesList($active, $with);
		return json_decode(json_encode($countryList), true);
	}

	/**
	 * 获取国家数据 树状
	 * @return array
	 */
	function getTree ()
	{
		$country_list = $this->getAll();
		$country = [];
		foreach ($country_list as $c) {
			$states = [];
			foreach ($c['states'] as $state) {
				$states[] = [
					'id' => $state['id'],
					'name' => $state['name']
				];
			}

			foreach ($c['names'] as $c_name) {
				if ($c_name['language'] == Utils::getLang()) {
					$country[] = [
						'id' => $c_name['country_id'],
						'name' => $c_name['name'],
						'states' => $states
					];
				}
			}
		}
		return $country;
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
