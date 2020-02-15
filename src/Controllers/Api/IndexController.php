<?php

namespace Plentymarket\Controllers\Api;

use Plenty\Plugin\Http\Request;
use Plenty\Plugin\Http\Response;
use Plentymarket\Controllers\BaseApiController;
use Plentymarket\Services\AccountService;
use Plentymarket\Services\BlogService;
use Plentymarket\Services\CategoryService;
use Plentymarket\Services\ItemSetService;
use Plentymarket\Services\PaymentMethodService;
use Plentymarket\Services\StockService;
use Plentymarket\Services\WarehouseService;

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
	 * @return Response
	 */
	public function login (): Response
	{
		$email = $this->request->get('email');
		$password = $this->request->get('password');

		if (empty($email) || empty($password)) {
			return $this->error($this->trans("ApiIndex.loginEmailOrPasswordError"));
		}

		if ($this->accountService->login($email, $password)) {
			return $this->success($this->trans('ApiIndex.loginSuccess'));
		} else {
			return $this->error($this->trans('ApiIndex.loginEmailOrPasswordError'));
		}
	}

	/**
	 * @return Response
	 */
	public function register (): Response
	{
		$email = $this->request->get('email');
		$password = $this->request->get('password');

		if (empty($email) || empty($password)) {
			return $this->error($this->trans("ApiIndex.registerEmailOrPasswordError"));
		}

		if ($this->accountService->register($email, $password)) {
			return $this->success($this->trans("ApiIndex.registerSuccess"));
		} else {
			return $this->error($this->trans("ApiIndex.registerEmailExist"));
		}
	}

	/**
	 * @return Response
	 */
	public function category (): Response
	{
		return $this->success(pluginApp(CategoryService::class)->getAll());
	}

	/**
	 * @return Response
	 */
	public function itemset (): Response
	{
		return $this->success(pluginApp(ItemSetService::class)->getAll());
	}

	/**
	 * @return Response
	 */
	public function warehouse (): Response
	{
		return $this->success(pluginApp(WarehouseService::class)->getAll());
	}

	/**
	 * @return Response
	 */
	public function stock (): Response
	{
		return $this->success(pluginApp(StockService::class)->listStock());
	}

	/**
	 * @return Response
	 */
	public function warehousestock (): Response
	{
		$warehouse_list = pluginApp(WarehouseService::class)->getAll();
		$stockService = pluginApp(StockService::class);
		$stock_list = [];
		foreach ($warehouse_list as $warehouse) {
			return $this->success($stockService->listStockByWarehouse($warehouse['id']));
			//$stock_list = array_merge($stock_list,$stockService->listStockByWarehouse($warehouse['id']));
		}
		return $this->success(pluginApp(StockService::class)->listStock());
	}

	/**
	 * @return Response
	 */
	public function blog (): Response
	{
		return $this->success(pluginApp(BlogService::class)->getAll());
	}
}
