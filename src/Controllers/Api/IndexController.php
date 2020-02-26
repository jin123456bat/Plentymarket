<?php

namespace Plentymarket\Controllers\Api;

use Plenty\Plugin\Http\Request;
use Plenty\Plugin\Http\Response;
use Plentymarket\Controllers\BaseApiController;
use Plentymarket\Services\AccountService;
use Plentymarket\Services\BlogService;
use Plentymarket\Services\CategoryService;
use Plentymarket\Services\ItemListService;
use Plentymarket\Services\ItemService;
use Plentymarket\Services\ItemSetService;
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
	 * 用户登录接口
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
	 * 用户注册接口
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
			//$stock_list = array_merge($stock_list, $stockService->listStockByWarehouse($warehouse['id'])->getResult());
		}
		return $this->success($stock_list);
	}

	/**
	 * @return Response
	 */
	public function item (): Response
	{
		return $this->success(pluginApp(ItemService::class)->getAll());
	}

	/**
	 * @return Response
	 */
	public function blog (): Response
	{
		return $this->success(pluginApp(BlogService::class)->getAll());
	}

	/**
	 * @return Response
	 */
	public function search (): Response
	{
		try {
			/** @var ItemListService $itemListService */
			$itemListService = pluginApp(ItemListService::class);

			$itemList = $itemListService->getCategoryItem(16, null, 1, 12);
			return $this->success($itemList);
		} catch (\Exception $e) {
			return $this->success([
				'code' => $e->getCode(),
				'file' => $e->getFile(),
				'message' => $e->getMessage(),
				'trace' => $e->getTrace(),
			]);
		}
	}

	public function test (): Response
	{
		return $this->success([
			'getUriForPath' => $this->request->getUriForPath(),
			'getUri' => $this->request->getUri(),
			'getRequestUri' => $this->request->getRequestUri(),
		]);
	}
}
