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
			$basketItem = $basket->create([
				'variationId' => $variationId,
				'quantity' => $quantity,
			]);
			return $this->success([
				'basketItemId' => $basketItem->id
			]);
		} catch (\Throwable $e) {
			return $this->exception($e);
		}
	}

	/**
	 * 更新购物车的商品数量
	 * @return Response
	 */
	function update (): Response
	{
		try {
			$cart = $this->request->input('cart');
			$cart_data = json_decode($cart, true);

			$basket_list = pluginApp(ItemListService::class)->getItemsFromBasket();
			$basket = pluginApp(BasketService::class);
			foreach ($basket_list as $item) {
				foreach ($cart_data as $c) {
					if ($item['basketItemId'] == $c['id'] && $item['quantity'] != $c['quantity']) {
						if (empty($c['quantity'])) {
							$basket->delete($item['basketItemId']);
						} else {
							if (!$basket->updateQuantity($item['basketItemId'], $c['quantity'])) {
								throw new \Exception('更新数量失败');
							}
						}
					}
				}
			}

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
			$total = 0;
			$itemListService = pluginApp(ItemListService::class);
			$list = $itemListService->getItemsFromBasket();
			foreach ($list as $value) {
				$total += ($value['quantity'] * $value['discount_price']);
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
