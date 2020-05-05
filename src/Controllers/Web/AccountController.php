<?php

namespace Plentymarket\Controllers\Web;

use Plenty\Plugin\Http\Request;
use Plenty\Plugin\Http\Response;
use Plentymarket\Controllers\BaseWebController;
use Plentymarket\Extensions\Filters\NumberFormatFilter;
use Plentymarket\Helper\Utils;
use Plentymarket\Services\AddressService;
use Plentymarket\Services\CountryService;
use Plentymarket\Services\ItemListService;
use Plentymarket\Services\OrderService;
use Plentymarket\Services\PaymentMethodService;

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
		/** @var OrderService $orderService */
		$orderService = pluginApp(OrderService::class);
		$orders = $orderService->getList(1, 10000);

		/** @var AddressService $addressOrders */
		$addressOrders = pluginApp(AddressService::class);
		$address = $addressOrders->getAll();

		return $this->render('account.index', [
			$this->trans('WebAccountIndex.account') => '/account/index'
		], [
			'orders' => $orders,
			'address' => $address,
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

		$country = pluginApp(CountryService::class)->getTree();

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
		try {
			$address = pluginApp(AddressService::class)->getAll();

			$total = 0;
			$itemListService = pluginApp(ItemListService::class);
			$list = $itemListService->getItemsFromBasket();
			foreach ($list as $value) {
				$total += ($value['quantity'] * $value['discount_price'] * (1 + $value['vat'] / 100));
			}

			$numberFormatFilter = pluginApp(NumberFormatFilter::class);

			$country = pluginApp(CountryService::class)->getTree();

			//支付方式暂时搞不定
			$paymentList = pluginApp(PaymentMethodService::class)->getAll();

			return $this->render('account.checkout', [
				$this->trans('WebAccountCheckout.checkout') => '/account/checkout'
			], [
				'addresses' => $address,
				'items' => $list,
				'total' => $numberFormatFilter->formatMonetary($total, Utils::getCurrency()),
				'ship' => $numberFormatFilter->formatMonetary(0, Utils::getCurrency()),
				'country' => $country,
				'paymentList' => [
					[
						'id' => 6000,
						'name' => 'PayPal',
					]
				],
			]);
		} catch (\Throwable $e) {
			return $this->exception($e);
		}
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
		], [
			'list' => $list
		]);
	}
}
