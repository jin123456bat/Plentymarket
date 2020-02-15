<?php

namespace Plentymarket\Services;

use Plenty\Modules\Order\Currency\Contracts\CurrencyRepositoryContract;
use Plenty\Modules\Order\Currency\Models\Currency;

/**
 * Class CurrencyService
 * @package Plentymarket\Services
 */
class CurrencyService
{
	/**
	 * @var CurrencyRepositoryContract
	 */
	private $currencyRepositoryContract;

	/**
	 * CurrencyService constructor.
	 * @param CurrencyRepositoryContract $currencyRepositoryContract
	 */
	function __construct (CurrencyRepositoryContract $currencyRepositoryContract)
	{
		$this->currencyRepositoryContract = $currencyRepositoryContract;
	}

	/**
	 * 获取所有的支持的货币列表
	 * @param array $with ["names", "countries"]
	 * @return mixed
	 */
	function getAll (array $with = ["names", "countries"])
	{
		return $this->currencyRepositoryContract->getCurrencyList([], $with);
	}

	/**
	 * 获取货币种类的模型
	 * @param string $currencyIso
	 * @param array $with ["names", "countries"]
	 * @return Currency
	 */
	function getModel (string $currencyIso, array $with = ["names", "countries"])
	{
		return $this->currencyRepositoryContract->getCurrency($currencyIso, [], $with);
	}
}
