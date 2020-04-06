<?php

namespace Plentymarket\Services;

use Plenty\Modules\Frontend\PaymentMethod\Contracts\FrontendPaymentMethodRepositoryContract;

class PaymentMethodService
{
	private $frontendPaymentMethodRepositoryContract;

	function __construct (FrontendPaymentMethodRepositoryContract $frontendPaymentMethodRepositoryContract)
	{
		$this->frontendPaymentMethodRepositoryContract = $frontendPaymentMethodRepositoryContract;
	}

	function getAll ()
	{
		return $this->frontendPaymentMethodRepositoryContract->getCurrentPaymentMethodsList();
	}
}
