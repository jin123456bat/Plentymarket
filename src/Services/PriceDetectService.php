<?php

namespace Plentymarket\Services;

use Plenty\Legacy\Services\Accounting\VatInitService;
use Plenty\Legacy\Services\Item\Variation\DetectSalesPriceService;
use Plenty\Modules\Account\Contact\Models\Contact;
use Plenty\Modules\Accounting\Vat\Contracts\VatInitContract;
use Plenty\Modules\Basket\Contracts\BasketRepositoryContract;
use Plenty\Modules\Frontend\Services\VatService;
use Plentymarket\Helper\Utils;

/**
 * Class PriceDetectService
 * @package Plentymarket\Services
 */
class PriceDetectService
{
	private $classId = null;
	private $singleAccess = null;
	private $shippingCountryId = null;

	/**
	 * @var DetectSalesPriceService
	 */
	private $detectSalesPriceService;

	/**
	 * @var AccountService
	 */
	private $accountService;

	/**
	 * @var CheckoutService
	 */
	private $checkoutService;

	/**
	 * @var VatService $vatService
	 */
	private $vatService;

	/**
	 * @var VatInitService $vatService
	 */
	private $vatInitService;

	private $referrerId;

	/**
	 * PriceDetectService constructor.
	 * @param DetectSalesPriceService $detectSalesPriceService
	 * @param AccountService $accountService
	 * @param CheckoutService $checkoutService
	 * @param VatInitContract $vatInitService
	 * @param VatService $vatService
	 */
	public function __construct (DetectSalesPriceService $detectSalesPriceService,
	                             AccountService $accountService,
	                             CheckoutService $checkoutService,
	                             VatInitContract $vatInitService,
	                             VatService $vatService)
	{
		$this->detectSalesPriceService = $detectSalesPriceService;
		$this->accountService = $accountService;
		$this->checkoutService = $checkoutService;
		$this->vatInitService = $vatInitService;
		$this->vatService = $vatService;

		$contact = $this->accountService->getContact();

		if ($contact instanceof Contact) {
			$this->singleAccess = $contact->singleAccess;
		}

		$this->classId = $this->accountService->getContactClassId();
		$this->shippingCountryId = $this->checkoutService->getShippingCountryId();

		$basket = pluginApp(BasketRepositoryContract::class)->load();

		$referrerId = (int)$basket->referrerId;
		$this->referrerId = ($referrerId > 0 ? $referrerId : 1);

		if (!$this->vatInitService->isInitialized()) {
			$vat = $this->vatService->getVat();
		}
	}

	public function getPriceIdsForCustomer ()
	{
		$this->detectSalesPriceService
			->setAccountId(0)
			->setAccountType($this->singleAccess)
			->setCountryOfDelivery($this->shippingCountryId)
			->setCurrency(Utils::getCurrency())
			->setCustomerClass($this->classId)
			->setOrderReferrer($this->referrerId)
			->setPlentyId(Utils::getPlentyId())
			->setQuantity(-1)
			->setType(DetectSalesPriceService::PRICE_TYPE_DEFAULT);

		return $this->detectSalesPriceService->detect();
	}
}
