<?php

namespace Plentymarket\Controllers\Api;

use Plenty\Modules\Order\Models\Order;
use Plenty\Modules\Payment\Contracts\PaymentOrderRelationRepositoryContract;
use Plenty\Modules\Payment\Contracts\PaymentRepositoryContract;
use Plenty\Modules\Payment\Models\Payment;
use Plenty\Plugin\Log\Loggable;
use Plentymarket\Controllers\BaseApiController;
use Plentymarket\Services\HttpService;
use Plentymarket\Services\OrderService;
use Plentymarket\Services\PayPalService;

class PaymentController extends BaseApiController
{
	private $url_test = 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr';
	private $url = 'https://ipnpb.paypal.com/cgi-bin/webscr';

	use Loggable;

	function verify (string $content): bool
	{
		$data = 'cmd=_notify-validate&' . $content;

		/** @var HttpService $http */
		$http = pluginApp(HttpService::class);
		$response = $http->post($this->url, $data, [
			'User-Agent' => 'PHP-IPN-Verification-Script',
		], [
			CURLOPT_SSL_VERIFYPEER => 1,
			CURLOPT_SSL_VERIFYHOST => 2,
			CURLOPT_SSLVERSION => 6,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_FORBID_REUSE => 1,
		]);

		if ($response == 'VERIFIED') {
			return true;
		}

		$this->getLogger(__CLASS__)->info(
			"Plentymarket::Payment.Paypal",
			[
				"resultName" => '异步验证失败:' . $data,
			]
		);
		return false;
	}

	/**
	 * array (size=38)
	 * 'mc_gross' => string '7.40' (length=4)
	 * 'invoice' => string '201 -' (length=5)
	 * 'protection_eligibility' => string 'Ineligible' (length=10)
	 * 'item_number1' => string 'Versandkosten' (length=13)
	 * 'item_number2' => string 'Barhocker White SanFrancisco' (length=28)
	 * 'payer_id' => string '8UTZGSX6P8KL6' (length=13)
	 * 'payment_date' => string '11:09:55 Apr 11, 2020 PDT' (length=25)
	 * 'payment_status' => string 'Pending' (length=7)
	 * 'charset' => string 'windows-1252' (length=12)
	 * 'first_name' => string 'John' (length=4)
	 * 'notify_version' => string '3.9' (length=3)
	 * 'custom' => string '120' (length=3)
	 * 'payer_status' => string 'verified' (length=8)
	 * 'num_cart_items' => string '2' (length=1)
	 * 'verify_sign' => string 'AUIn6d4LQ.-HOStAWDsaRvEW11rkAXPMR2oeOpgs-D9PJ4gh5YSOVgWa' (length=56) 签名验证
	 * 'payer_email' => string 'sb-ezqsa1307792@personal.example.com' (length=36)  支付者邮箱
	 * 'txn_id' => string '73U51092SW5708227' (length=17)
	 * 'payment_type' => string 'instant' (length=7)  支付方式
	 * 'last_name' => string 'Doe' (length=3)
	 * 'item_name1' => string 'Versandkosten' (length=13)
	 * 'receiver_email' => string 'info@mercuryliving.it' (length=21)  接受者邮箱
	 * 'item_name2' => string 'Barhocker White SanFrancisco' (length=28)
	 * 'shipping_discount' => string '0.00' (length=4)
	 * 'quantity1' => string '1' (length=1)
	 * 'insurance_amount' => string '0.00' (length=4)
	 * 'quantity2' => string '1' (length=1)
	 * 'pending_reason' => string 'unilateral' (length=10)
	 * 'txn_type' => string 'cart' (length=4)
	 * 'discount' => string '0.00' (length=4)
	 * 'mc_gross_1' => string '7.13' (length=4)
	 * 'mc_currency' => string 'EUR' (length=3)
	 * 'mc_gross_2' => string '0.27' (length=4)
	 * 'residence_country' => string 'IT' (length=2)
	 * 'test_ipn' => string '1' (length=1)
	 * 'shipping_method' => string 'Default' (length=7)
	 * 'transaction_subject' => string '' (length=0)
	 * 'payment_gross' => string '' (length=0)
	 * 'ipn_track_id' => string '3a4046afdbec6' (length=13)
	 * @return string
	 */
	function paypal (): string
	{
		try {
			$content = $this->request->getContent();
			if (empty($content)) {
				$this->getLogger(__CLASS__)->error(
					"Plentymarket::Payment.Paypal",
					[
						"resultName" => '请求内容为空',
						"errorMessage" => '请求内容为空'
					]
				);

				return 'failed';
			}

			$contentArray = $this->request->all();

			if ($this->verify($content)) {
				/** @var OrderService $orderService */
				$orderService = pluginApp(OrderService::class);
				list($orderId, $accessKey) = explode('|', $contentArray['custom'], 2);
				$order = $orderService->getByAccessKey($orderId, $accessKey);

				//在验证一下金额
				if ($this->calcAmount($order) == $contentArray['mc_gross']) {
					//修改订单状态
					$this->getLogger(__CLASS__)->error(
						"Plentymarket::Payment.Paypal",
						[
							"resultName" => '金额验证成功',
						]
					);

					$this->updateOrder($order, $contentArray);
					return 'success';
				} else {
					$this->getLogger(__CLASS__)->error(
						"Plentymarket::Payment.Paypal",
						[
							"resultName" => '金额验证失败',
						]
					);
				}
			}
			return 'failed';
		} catch (\Throwable $e) {
			$this->getLogger(__CLASS__)->error(
				"Plentymarket::Payment.Paypal",
				[
					"resultName" => 'Exception',
					'message' => $e->getMessage(),
					'code' => $e->getCode(),
					'file' => $e->getFile(),
					'line' => $e->getLine(),
					'trace' => $e->getTrace(),
					'request' => $this->request->all(),
				]
			);
			return 'exception';
		}
	}

	function calcAmount (Order $order, bool $includeVat = true)
	{
		$order_amount = 0;
		foreach ($order->orderItems as $key => $item) {
			$amount = PayPalService::getAmount($item['amounts']);
			$order_amount += round($item['quantity'] * ($amount['priceGross'] * (1 + ($includeVat ? $item['vatRate'] : 0) / 100)), 2);
		}
		return $order_amount;
	}

	function updateOrder (Order $order, array $data)
	{
		//创建一个payment
		/** @var PaymentOrderRelationRepositoryContract $paymentOrderRelationRepositoryContract */
		$paymentOrderRelationRepositoryContract = pluginApp(PaymentOrderRelationRepositoryContract::class);
		$payment = pluginApp(Payment::class);

		$payment->mopId = 6000;
		$payment->transactionType = 2;
		$payment->status = 2;
		$payment->currency = $data['mc_currency'];
		$payment->amount = $this->calcAmount($order, false);
		$payment->receivedAt = date('Y-m-d H:i:s');
		$payment->type = 'credit';
		//$payment->parentId = null;
//		$payment->unaccountable = null;
		$payment->regenerateHash = true;
		$payment->updateOrderPaymentStatus = true;
		/** @var PaymentRepositoryContract $paymentRepositoryContract */
		$paymentRepositoryContract = pluginApp(PaymentRepositoryContract::class);
		$paymentRepositoryContract->createPayment($payment);

		$paymentOrderRelationRepositoryContract->createOrderRelation($payment, $order);

		$this->getLogger(__CLASS__)->error(
			"Plentymarket::Payment.Paypal",
			[
				"resultName" => '修改订单状态成功',
			]
		);
	}
}
