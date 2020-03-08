<?php

namespace Plentymarket\Controllers\Web;

use Plenty\Plugin\Http\Request;
use Plenty\Plugin\Http\Response;
use Plentymarket\Controllers\BaseWebController;
use Plentymarket\Extensions\Filters\NumberFormatFilter;
use Plentymarket\Helper\Utils;
use Plentymarket\Services\CountryService;
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
		$ship = 0;//运费
		$list = pluginApp(ItemListService::class)->getItemsFromBasket();
		$virtaul_cart = [];
		foreach ($list as $r) {
			$total += ($r['quantity'] * $r['discount_price']);
			$vat += ($r['quantity'] * $r['discount_price'] * $r['vat'] / 100);

			$virtaul_cart[] = [
				'id' => $r['basketItemId'],
				'quantity' => $r['quantity'],
			];
		}

		$numberFormatFilter = pluginApp(NumberFormatFilter::class);

		$country_list = pluginApp(CountryService::class)->getAll();
		$country = [];
		foreach ($country_list as $c) {
			$states = [];
			foreach ($c['states'] as $state) {
				$states[] = [
					'id' => $state['id'],
					'name' => $state['name']
				];
			}

			foreach ($c['names'] as $c_name) {
				if ($c_name['language'] == Utils::getLang()) {
					$country[] = [
						'id' => $c_name['country_id'],
						'name' => $c_name['name'],
						'states' => $states
					];
				}
			}
		}

		return $this->render('account.cart', [
			$this->trans('WebAccountCart.cart') => '/account/cart'
		], [
			'list' => $list,
			'total' => $numberFormatFilter->formatMonetary($total, Utils::getCurrency()),
			'vat' => $numberFormatFilter->formatMonetary($vat, Utils::getCurrency()),
			'ship' => $numberFormatFilter->formatMonetary($ship, Utils::getCurrency()),
			'summary' => $numberFormatFilter->formatMonetary($total + $vat + $ship, Utils::getCurrency()),
			'country' => $country,
			'virtual_cart' => json_encode($virtaul_cart),
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
		$list = pluginApp(ItemListService::class)->getItemsFromWishlist();

		return $this->render('account.wishlist', [
			$this->trans('WebAccountWishlist.wishlist') => '/account/wishlist'
		]);
	}
}
