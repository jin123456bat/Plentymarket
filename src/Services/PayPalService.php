<?php

namespace Plentymarket\Services;

use Plentymarket\Helper\Utils;
use Plentymarket\Models\PayPalAccessToken;

/**
 * Class PayPalService
 * @package Plentymarket\Services
 */
class PayPalService
{
	//https://api.paypal.com
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

	/**
	 * 跳转到支付页面
	 * @param string $price
	 */
	function execute (string $price)
	{
		$str = '<form id="form" method="post" action="https://www.sandbox.paypal.com/cgi-bin/webscr&pal=V4T754QB63XXL">
	<input type="hidden" name="cmd" value="_cart" />
    <input type="hidden" name="business" value="info@mercuryliving.it" />
    <input type="hidden" name="amount" value="' . $price . '" />
    <input type="hidden" name="currency_code" value="EUR" />
    <input type="hidden" name="charset" value="utf-8" />
    <input type="hidden" name="return" value="http://mobile.cn/index.php?route=checkout/success" />
    <input type="hidden" name="notify_url" value="http://mobile.cn/index.php?route=extension/payment/pp_standard/callback" />
    <input type="hidden" name="cancel_return" value="http://mobile.cn/index.php?route=checkout/checkout" />
    <input type="submit" id="submit" value="submit">
    </form>
    <script>document.getElementById("form").submit();</script>';

//		$url = 'https://www.paypal.com/cgi-bin/webscr&pal=V4T754QB63XXL';
//		$url = 'https://www.paypal.com/cgi-bin/webscr&pal='.$pal;
//		$param = '';
//		foreach ($data as $key => $value) {
//			$param .= '<input type="hidden" name="' . $key . '" value="' . $value . '">';
//		}
//		$param .= '<input type="submit" id="submit" value="submit"><script>document.getElementById(\'form\').submit();</script>';
//		$str = '<form id="form" action="' . $url . '" method="post">' . $param . '</form>';
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
