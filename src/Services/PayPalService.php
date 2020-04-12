<?php

namespace Plentymarket\Services;

use Plenty\Modules\Order\Models\Order;
use Plentymarket\Guards\AuthGuard;
use Plentymarket\Helper\Utils;
use Plentymarket\Models\PayPalAccessToken;

/**
 * Class PayPalService
 * @package Plentymarket\Services
 */
class PayPalService
{
	private $web = 'https://www.sandbox.paypal.com';

	/**
	 * @var string
	 */
	private $host = 'https://api.sandbox.paypal.com';

	private $client_id = 'AS9I0npDdg-n2Ia02jbN-cb9l2dSECVd_urkBFCZyGo5cvW8RATra_HZqD_NtZwnEwWDaiyFh_-RD6ji';

	private $secret = 'EM2gYP-Yv2c5BqONzj8-Mj9O1ZBi3Sgjmv4t5ZmpYtGz31mCWIqS3Fnb9cUH3S8rYjt6cNWcGvXEvu0L';

	/**
	 * @var HttpService
	 */
	private $httpService;

	/**
	 * PayPalService constructor.
	 * @param HttpService $httpService
	 */
	function __construct (HttpService $httpService)
	{
		$this->httpService = $httpService;
	}

	public function createOrder ($price)
	{
		$url = $this->host . '/v2/checkout/orders';
		$response = $this->httpService->post($url, json_encode([
			'intent' => 'CAPTURE',
			'purchase_units' => [
				[
					'amount' => [
						'currency_code' => $this->getLang(),
						'value' => $price
					]
				]
			]
		]), [
			'Content-Type' => 'application/json',
			'Authorization' => $this->getAuthorization()
		]);

		return json_decode($response, true);
	}

	private function getLang ()
	{
		return Utils::getLang();
	}

	private function getCurrency ()
	{
		return Utils::getCurrency();
	}

	public static function getAmount ($amounts)
	{
		foreach ($amounts as $amount) {
			if ($amount['currency'] == self::getCurrency()) {
				return $amount;
			}
		}
	}

	/**
	 * 跳转到支付页面
	 * @param Order $order
	 */
	function execute (Order $order)
	{
		$return = AuthGuard::getUrl('/account/index#order');//支付成功跳转地址
		$notify_url = AuthGuard::getUrl('/api/payment/paypal');//支付成功异步通知地址
		$cancel_return = $return;//取消支付跳转地址

		$str = '<form method="post" id="form" name="form" action="' . $this->web . '/cgi-bin/webscr&pal=V4T754QB63XXL">
		<input type="hidden" name="cmd" value="_cart" />
	    <input type="hidden" name="upload" value="1" />
	    <input type="hidden" name="business" value="info@mercuryliving.it" />';
		foreach ($order->orderItems as $key => $item) {
			$amount = self::getAmount($item['amounts']);
			$str .= '<input type="hidden" name="item_name_' . ($key + 1) . '" value="' . $item['orderItemName'] . '" />
			  <input type="hidden" name="item_number_' . ($key + 1) . '" value="' . $item['orderItemName'] . '" />
			  <input type="hidden" name="amount_' . ($key + 1) . '" value="' . round($item['quantity'] * ($amount['priceGross'] * (1 + $item['vatRate'] / 100)), 2) . '" />
			  <input type="hidden" name="quantity_' . ($key + 1) . '" value="' . $item['quantity'] . '" />
			  <input type="hidden" name="weight_' . ($key + 1) . '" value="0" />';
		}

		$str .= '<input type="hidden" name="currency_code" value="' . $this->getCurrency() . '" />
		<input type="hidden" name="first_name" value="" />
		<input type="hidden" name="last_name" value="" />
		<input type="hidden" name="address1" value="" />
		<input type="hidden" name="address2" value="" />
		<input type="hidden" name="city" value="" />
		<input type="hidden" name="zip" value="" />
		<input type="hidden" name="country" value="" />
		<input type="hidden" name="address_override" value="0" />
		<input type="hidden" name="email" value="326550324@qq.com" />
		<input type="hidden" name="invoice" value="201 -  " />
		<input type="hidden" name="lc" value="it-it" />
		<input type="hidden" name="rm" value="2" />
		<input type="hidden" name="no_note" value="1" />
		<input type="hidden" name="no_shipping" value="1" />
		<input type="hidden" name="charset" value="utf-8" />
		<input type="hidden" name="paymentaction" value="sale" />
		<input type="hidden" name="custom" value="' . $order->id . '" />
		<input type="hidden" name="return" value="' . $return . '" />
		<input type="hidden" name="notify_url" value="' . $notify_url . '" />
		<input type="hidden" name="cancel_return" value="' . $cancel_return . '" />
		<input type="hidden" name="bn" value="OpenCart_2.0_WPS" />
		<input type="submit" id="submit" value="submit">
		</form>
		<script>document.getElementById("submit").click();</script>';
		exit($str);
	}

	/**
	 * 获取调用其他接口的auth
	 * @return string
	 */
	private function getAuthorization ()
	{
		return 'Basic ' . base64_encode($this->client_id . ':' . $this->secret);
//		return 'Bearer ' . $this->getAccessToken();
	}

	/**
	 * 获取AccessToken
	 * @return string
	 */
	private function getAccessToken ()
	{
		/** @var PayPalAccessToken $paypalAccessToken */
		$paypalAccessToken = pluginApp(PayPalAccessToken::class);
		if (empty($paypalAccessToken) || empty($paypalAccessToken->created_at) || time() > strtotime($paypalAccessToken->created_at) + $paypalAccessToken->expires_in) {
			$url = $this->host . '/v1/oauth2/token';
			$response = $this->httpService->post($url, [
				'grant_type' => 'client_credentials',
			], [
				'Authorization' => 'Basic ' . base64_encode($this->client_id . ':' . $this->secret)
			]);

			$response = json_decode($response, true);

			$paypalAccessToken->access_token = $response['access_token'];
			$paypalAccessToken->scope = $response['scope'];
			$paypalAccessToken->token_type = $response['token_type'];
			$paypalAccessToken->app_id = $response['app_id'];
			$paypalAccessToken->expires_in = $response['expires_in'];
			$paypalAccessToken->nonce = $response['nonce'];
			$paypalAccessToken->created_at = date('Y-m-d H:i:s');
		}

		return $paypalAccessToken->access_token;
	}
}
