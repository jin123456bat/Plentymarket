<?php

namespace Plentymarket\Helper;

use Plenty\Modules\Accounting\Vat\Contracts\VatRepositoryContract;
use Plentymarket\Services\AccountService;

/**
 * Class VatConverter
 * @package Plentymarket\Helper
 */
class VatConverter
{
	/** @var VatRepositoryContract $vatRepo */
	private $vatRepo;

	/**
	 * VatConverter constructor.
	 * @param VatRepositoryContract $vatRepo
	 */
	public function __construct (VatRepositoryContract $vatRepo)
	{
		$this->vatRepo = $vatRepo;
	}

	/**
	 * @param float $amount
	 * @return float|int
	 */
	public function convertToGross ($amount)
	{
		/** @var AccountService $accountService */
		$accountService = pluginApp(AccountService::class);
		$contactClassData = $accountService->getContactClassData($accountService->getContactClassId());

		if (isset($contactClassData['showNetPrice']) && $contactClassData['showNetPrice']) {
			return $amount + (($amount * $this->getDefaultVat()->vatRate) / 100);
		}

		return $amount;
	}

	/**
	 * @return mixed
	 */
	public function getDefaultVat ()
	{
		return $this->vatRepo->getStandardVat(Utils::getPlentyId())->vatRates->first();
	}
}
