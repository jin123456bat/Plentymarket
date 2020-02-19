<?php

namespace Plentymarket\Services;

use Plenty\Modules\Frontend\Contracts\Checkout;
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
}
