<?php

namespace Plentymarket\Controllers\Api;

use Plenty\Modules\Order\Models\Order;
use Plenty\Modules\Payment\Contracts\PaymentOrderRelationRepositoryContract;
use Plenty\Modules\Payment\Contracts\PaymentRepositoryContract;
use Plenty\Modules\Payment\Models\Payment;
use Plenty\Plugin\Http\Request;
use Plenty\Plugin\Http\Response;
use Plentymarket\Controllers\BaseApiController;
use Plentymarket\Services\AccountService;
use Plentymarket\Services\CountryService;
use Plentymarket\Services\ItemListService;
use Plentymarket\Services\OrderService;
use Plentymarket\Services\PayPalService;
use Throwable;

/**
 * Class IndexController
 * @package Plentymarket\Controllers\Api
 */
class IndexController extends BaseApiController
{
	/**
	 * @var AccountService
	 */
	private $accountService;

	/**
	 * IndexController constructor.
	 * @param Request $request
	 * @param Response $response
	 * @param AccountService $accountService
	 */
	function __construct (Request $request, Response $response, AccountService $accountService)
	{
		parent::__construct($request, $response);
		$this->accountService = $accountService;
	}

	/**
	 * 用户登录接口
	 * @return Response
	 */
	public function login (): Response
	{
		$email = $this->request->get('email');
		$password = $this->request->get('password');

		if (empty($email) || empty($password)) {
			return $this->error('');
		}

		try {
			$this->accountService->login($email, $password);
			return $this->success([]);
		} catch (\Throwable $e) {
			return $this->exception($e);
		}
	}

	/**
	 * 用户注册接口
	 * @return Response
	 */
	public function register (): Response
	{
		$email = $this->request->get('email');
		$password = $this->request->get('password');

		if (empty($email) || empty($password)) {
			return $this->error('');
		}

		if ($this->accountService->register($email, $password)) {
			return $this->success([]);
		} else {
			return $this->error('');
		}
	}

	/**
	 * 获取商品信息
	 * @param $product_id
	 * @return Response
	 */
	public function product ($product_id): Response
	{
		try {
			/** @var ItemListService $itemListService */
			$itemListService = pluginApp(ItemListService::class);
			$item = $itemListService->getItem($product_id);
			return $this->success($item);
		} catch (\Exception $e) {
			return $this->exception($e);
		}
	}

	/**
	 * 根据国家ID获取城市信息
	 * @return Response
	 */
	public function state (): Response
	{
		try {
			$country_id = $this->request->input('country_id');
			$country_list = pluginApp(CountryService::class)->getAll();
			$states = [];
			foreach ($country_list as $c) {
				if ($c['id'] == $country_id) {
					foreach ($c['states'] as $state) {
						$states[] = [
							'id' => $state['id'],
							'name' => $state['name'],
							'country_id' => $state['countryId'],
						];
					}
				}
			}

			return $this->success($states);
		} catch (Throwable $e) {
			return $this->exception($e);
		}
	}

	function calcAmount (Order $order)
	{
		$order_amount = 0;
		foreach ($order->orderItems as $key => $item) {
			$amount = PayPalService::getAmount($item['amounts']);
			$order_amount += round($item['quantity'] * ($amount['priceGross'] * (1 + $item['vatRate'] / 100)), 2);
		}
		return $order_amount;
	}

	/**
	 * test
	 * @return Response
	 */
	public function test (): Response
	{
		/** @var OrderService $orderService */
		$orderService = pluginApp(OrderService::class);
		$order = $orderService->getModel(149);

		//创建一个payment
		/** @var PaymentOrderRelationRepositoryContract $paymentOrderRelationRepositoryContract */
		$paymentOrderRelationRepositoryContract = pluginApp(PaymentOrderRelationRepositoryContract::class);
		$payment = pluginApp(Payment::class);

		$payment->mopId = 6000;
		$payment->transactionType = 2;
		$payment->status = 2;
		$payment->currency = 'EUR';
		$payment->amount = $this->calcAmount($order);
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

		exit('success');
	}
}
