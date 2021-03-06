<?php

namespace Plentymarket\Services;

use Plenty\Modules\Frontend\Contracts\Checkout;
use Plenty\Modules\Frontend\PaymentMethod\Contracts\FrontendPaymentMethodRepositoryContract;
use Plentymarket\Helper\Utils;

class CheckoutService
{
	private $checkout;

	function __construct (Checkout $checkout)
	{
		$this->checkout = $checkout;
	}

	/**
	 * Get the ID of the current shipping country
	 * @return int
	 */
	public function getShippingCountryId ()
	{
		$currentShippingCountryId = (int)$this->checkout->getShippingCountryId();
		if ($currentShippingCountryId <= 0) {
			$currentShippingCountryId = pluginApp(ConfigService::class)->getWebsiteConfig('defaultShippingCountryList')[Utils::getLang()];
		}

		if ($currentShippingCountryId <= 0) {
			$currentShippingCountryId = pluginApp(ConfigService::class)->getWebsiteConfig('defaultShippingCountryId');
		}

		return $currentShippingCountryId;
	}

	public function setMethodOfPaymentId ($paymentMethodId)
	{
		$this->checkout->setPaymentMethodId($paymentMethodId);
	}

	/**
	 * Get the ID of the current payment method
	 * @return int
	 */
	public function getMethodOfPaymentId ()
	{
		$methodOfPaymentID = (int)$this->checkout->getPaymentMethodId();

		/** @var FrontendPaymentMethodRepositoryContract $frontendPaymentMethodRepository */
		$frontendPaymentMethodRepository = pluginApp(FrontendPaymentMethodRepositoryContract::class);

		$methodOfPaymentList = $frontendPaymentMethodRepository->getCurrentPaymentMethodsList();
		$methodOfPaymentExpressCheckoutList = $frontendPaymentMethodRepository->getCurrentPaymentMethodsForExpressCheckout();
		$methodOfPaymentList = array_merge($methodOfPaymentList, $methodOfPaymentExpressCheckoutList);

		$methodOfPaymentValid = false;
		foreach ($methodOfPaymentList as $methodOfPayment) {
			if ((int)$methodOfPaymentID == $methodOfPayment->id) {
				$methodOfPaymentValid = true;
			}
		}

		if ($methodOfPaymentID === null || !$methodOfPaymentValid) {
			$methodOfPaymentID = $methodOfPaymentList[0]->id;

			if (!is_null($methodOfPaymentID)) {
				$this->checkout->setPaymentMethodId($methodOfPaymentID);
				pluginApp(SessionService::class)->set('MethodOfPaymentID', $methodOfPaymentID);
			}
		}

		return $methodOfPaymentID;
	}

}
