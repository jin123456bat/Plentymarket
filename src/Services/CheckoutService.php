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

	/**
	 * Get the ID of the current payment method
	 * @return int
	 */
	public function getMethodOfPaymentId ()
	{
		$methodOfPaymentID = (int)$this->checkout->getPaymentMethodId();

		$methodOfPaymentList = pluginApp(FrontendPaymentMethodRepositoryContract::class)->getCurrentPaymentMethodsList();

		$methodOfPaymentExpressCheckoutList = $this->getMethodOfPaymentExpressCheckoutList();
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
				$this->setMethodOfPaymentId($methodOfPaymentID);
			}
		}

		return $methodOfPaymentID;
	}

}
