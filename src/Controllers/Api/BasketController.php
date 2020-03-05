<?php

namespace Plentymarket\Controllers\Api;

use Plenty\Plugin\Http\Response;
use Plentymarket\Controllers\BaseApiController;
use Plentymarket\Services\BasketService;
use Plentymarket\Services\ItemListService;

/**
 * Class BasketController
 * @package Plentymarket\Controllers\Api
 */
class BasketController extends BaseApiController
{
	/**
	 * 调整购物车中的商品数量
	 * @return Response
	 */
	function create (): Response
	{
		try {
			$variationId = $this->request->input('variationId');
			$quantity = $this->request->input('quantity');

			$basket = pluginApp(BasketService::class);
			$basket->create([
				'variationId' => $variationId,
				'quantity' => $quantity,
			]);
			return $this->success([]);
		} catch (\Throwable $e) {
			return $this->exception($e);
		}
	}

	/**
	 * 删除购物车中的商品，不论数量
	 * @return Response
	 */
	function delete ()
	{
		try {
			$basketItemId = $this->request->input('basketItemId');

			$basket = pluginApp(BasketService::class);
			$basket->delete($basketItemId);
			return $this->success([]);
		} catch (\Throwable $e) {
			return $this->exception($e);
		}
	}

	/**
	 * 购物车列表
	 * @return Response
	 */
	function index (): Response
	{
		try {
			$list = pluginApp(BasketService::class)->getAll();
			$variationId = [];
			$dict = [];
			$total = 0;
			$itemListService = pluginApp(ItemListService::class);
			foreach ($list as $value) {
				$variationId[] = $value['variationId'];
				$dict[$value['variationId']] = [
					'quantity' => $value['quantity'],
					'basketItemId' => $value['id'],
				];
				$total += $value['quantity'] * $value['price'];
			}
			$list = $itemListService->getItemVariationIds($variationId);
			foreach ($list as &$value) {
				$value['image'] = current($value['images']);
				$value['quantity'] = $dict[$value['variationId']]['quantity'];
				$value['basketItemId'] = $dict[$value['variationId']]['basketItemId'];
			}
			return $this->success([
				'list' => $list,
				'total' => $total,
			]);
		} catch (\Throwable $e) {
			return $this->exception($e);
		}
	}

	/**
	 * 购物车中商品数量
	 * @return Response
	 */
	function num (): Response
	{
		try {
			$list = pluginApp(BasketService::class)->getAll();
			$quantity = 0;
			foreach ($list as $value) {
				$quantity += $value['quantity'];
			}
			return $this->success($quantity);
		} catch (\Throwable $e) {
			return $this->exception($e);
		}
	}
}
