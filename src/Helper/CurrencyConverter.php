<?php

namespace Plentymarket\Helper;

use Plenty\Modules\Frontend\Contracts\CurrencyExchangeRepositoryContract;

/**
 * Class CurrencyConverter
 * @package Plentymarket\Helper
 */
class CurrencyConverter
{
	/** @var CurrencyExchangeRepositoryContract $currencyExchcangeRepo */
	private $currencyExchcangeRepo;

	/**
	 * CurrencyConverter constructor.
	 * @param CurrencyExchangeRepositoryContract $currencyExchangeRepo
	 */
	public function __construct (CurrencyExchangeRepositoryContract $currencyExchangeRepo)
	{
		$this->currencyExchcangeRepo = $currencyExchangeRepo;
	}

	/**
	 * @param float $amount
	 * @return float
	 */
	public function convertToDefaultCurrency ($amount)
	{
		if (!$this->isCurrentCurrencyDefault()) {
			return $this->currencyExchcangeRepo->convertToDefaultCurrency(Utils::getCurrency(), $amount);
		}

		return $amount;
	}

	/**
	 * @return bool
	 */
	public function isCurrentCurrencyDefault ()
	{
		return Utils::getCurrency() == $this->getDefaultCurrency();
	}

	/**
	 * @return string
	 */
	public function getDefaultCurrency ()
	{
		return $this->currencyExchcangeRepo->getDefaultCurrency();
	}
}
