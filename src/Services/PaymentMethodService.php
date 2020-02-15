<?php

namespace Plentymarket\Services;

use Plenty\Modules\Order\Payment\Method\Contracts\PaymentMethodRepositoryContract;

/**
 * Class PaymentMethodService
 * @package Plentymarket\Services
 */
class PaymentMethodService
{
	/**
	 * @var PaymentMethodRepositoryContract
	 */
	private $paymentMethodRepositoryContract;

	/**
	 * PaymentMethodService constructor.
	 * @param PaymentMethodRepositoryContract $paymentMethodRepositoryContract
	 */
	function __construct (PaymentMethodRepositoryContract $paymentMethodRepositoryContract)
	{
		$this->paymentMethodRepositoryContract = $paymentMethodRepositoryContract;
	}

	/**
	 * 获取所有的支付方式
	 * @param $country_id
	 * @return array
	 */
	function getAll ($country_id)
	{
		$lang = pluginApp(SessionService::class)->getLang();
		return $this->paymentMethodRepositoryContract->getPaymentMethods($country_id, null, $lang);
	}
}
