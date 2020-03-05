<?php

namespace Plentymarket\Controllers\Web;

use Plenty\Plugin\Http\Request;
use Plenty\Plugin\Http\Response;
use Plentymarket\Controllers\BaseWebController;
use Plentymarket\Services\BasketService;
use Plentymarket\Services\ItemListService;

/**
 * Class AccountController
 * @package Plentymarket\Controllers\Web
 */
class AccountController extends BaseWebController
{
	function __construct (Request $request, Response $response)
	{
		parent::__construct($request, $response);
	}

	/**
	 * 个人中心首页
	 * @return string
	 */
	function index (): string
	{
		return $this->render('account.index', [
			$this->trans('WebAccountIndex.account') => '/account/index'
		]);
	}

	/**
	 * 购物车页面
	 * @return string
	 */
	function cart (): string
	{
		$list = pluginApp(BasketService::class)->getAll();
		$variationId = [];
		$dict = [];
		$total = 0;//商品累计
		$vat = 0;//VAT累计
		$itemListService = pluginApp(ItemListService::class);
		foreach ($list as $value) {
			$variationId[] = $value['variationId'];
			$dict[$value['variationId']] = [
				'quantity' => $value['quantity'],
				'basketItemId' => $value['id'],
			];
			$total += $value['quantity'] * $value['price'];
			$vat += $value['quantity'] * $value['price'] * $value['vat'] / 100;
		}
		$item_list = $itemListService->getItemVariationIds($variationId);
		foreach ($item_list as $key => $r) {
			$r[$key]['image'] = current($r['images']);
			$r[$key]['quantity'] = $dict[$r['variationId']]['quantity'];
			$r[$key]['basketItemId'] = $dict[$r['variationId']]['basketItemId'];
		}

		return $this->render('account.cart', [
			$this->trans('WebAccountCart.cart') => '/account/cart'
		], [
			'list' => $item_list,
			'total' => $total,
			'vat' => $vat,
		]);
	}

	/**
	 * 结算
	 * @return string
	 */
	function checkout (): string
	{
		return $this->render('account.checkout', [
			$this->trans('WebAccountCheckout.checkout') => '/account/checkout'
		]);
	}

	/**
	 * 愿望清单
	 * @return string
	 */
	function wishlist (): string
	{
		return $this->render('account.wishlist', [
			$this->trans('WebAccountWishlist.wishlist') => '/account/wishlist'
		]);
	}
}
