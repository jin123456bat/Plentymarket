<?php

namespace Plentymarket\Controllers\Web;

use Plentymarket\Controllers\BaseWebController;

/**
 * Class AccountController
 * @package Plentymarket\Controllers\Web
 */
class AccountController extends BaseWebController
{
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
		return $this->render('account.cart', [
			$this->trans('WebAccountCart.cart') => '/account/cart'
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
