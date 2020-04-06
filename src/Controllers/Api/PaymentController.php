<?php

namespace Plentymarket\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Plenty\Plugin\Log\Loggable;
use Plentymarket\Controllers\BaseApiController;

class PaymentController extends BaseApiController
{
	use Loggable;

	function paypal (): JsonResponse
	{
		$content = file_get_contents('php://input');
		if (empty($content)) {
			$this->getLogger(__CLASS__)->error(
				"Plentymarket::Payment.Paypal",
				[
					"resultName" => '请求内容为空',
					"errorMessage" => '请求内容为空'
				]
			);

			return $this->error('请求内容为空');
		}

		$this->getLogger(__CLASS__)->error(
			"Plentymarket::Payment.Paypal",
			[
				"resultName" => '获取到PayPal异步通知:' . $content,
				"errorMessage" => '获取到PayPal异步通知:' . $content,
			]
		);

		$content = json_decode($content, true);

		$this->success($content);
	}
}
