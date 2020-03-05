<?php

namespace Plentymarket\Controllers\Web;

use Plenty\Plugin\Http\Request;
use Plenty\Plugin\Http\Response;
use Plentymarket\Controllers\BaseWebController;
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
		$total = 0;//商品总金额
		$vat = 0;//增值税
		$list = pluginApp(ItemListService::class)->getItemsFromBasket();
		foreach ($list as $r) {
			$total += ($r['quantity'] * $r['price']);
			$vat += ($r['quantity'] * $r['price'] * $r['vat'] / 100);
		}

		return $this->render('account.cart', [
			$this->trans('WebAccountCart.cart') => '/account/cart'
		], [
			'list' => $list,
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
