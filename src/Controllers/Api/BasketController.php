<?php

namespace Plentymarket\Controllers\Api;

use Plenty\Plugin\Http\Response;
use Plentymarket\Controllers\BaseApiController;
use Plentymarket\Services\BasketService;

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
	 * 购物车中商品数量
	 * @return Response
	 */
	function num (): Response
	{
		try {
			return $this->success(pluginApp(BasketService::class)->getAll());
		} catch (\Throwable $e) {
			return $this->exception($e);
		}
	}
}
