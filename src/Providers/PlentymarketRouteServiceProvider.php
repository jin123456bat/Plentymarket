<?php

namespace Plentymarket\Providers;

use Plenty\Plugin\RouteServiceProvider;
use Plenty\Plugin\Routing\Router;

/**
 * Class PlentymarketRouteServiceProvider
 * @package Plentymarket\Providers
 */
class PlentymarketRouteServiceProvider extends RouteServiceProvider
{
	/**
	 * @param Router $router
	 */
	public function map (Router $router)
	{
		//接口声明
//		$api->version(['v1'], ['namespace' => 'Plentymarket\Controllers\Api'], function (ApiRouter $api) {
//			$api->post('api/index/login', 'IndexController@login');
//			$api->post('api/index/register', 'IndexController@register');
//		});

		//测试api
		$router->get('/api/index/blog', 'Plentymarket\Controllers\Api\IndexController@blog');
		$router->get('/api/index/search', 'Plentymarket\Controllers\Api\IndexController@search');
		$router->get('/api/index/test', 'Plentymarket\Controllers\Api\IndexController@test');
		$router->post('/api/index/data', 'Plentymarket\Controllers\Api\IndexController@data');
		$router->get('/api/index/country', 'Plentymarket\Controllers\Api\IndexController@country');
		$router->get('/api/index/payment', 'Plentymarket\Controllers\Api\IndexController@payment');
		$router->get('/api/basket/all', 'Plentymarket\Controllers\Api\BasketController@all');
		$router->get('/api/order/index', 'Plentymarket\Controllers\Api\OrderController@index');

		//生产api
		$router->get('/api/index/register', 'Plentymarket\Controllers\Api\IndexController@register');
		$router->get('/api/index/login', 'Plentymarket\Controllers\Api\IndexController@login');
		$router->get('/api/index/state', 'Plentymarket\Controllers\Api\IndexController@state');
		$router->get('/api/basket/create', 'Plentymarket\Controllers\Api\BasketController@create');
		$router->get('/api/basket/delete', 'Plentymarket\Controllers\Api\BasketController@delete');
		$router->get('/api/basket/index', 'Plentymarket\Controllers\Api\BasketController@index');
		$router->get('/api/basket/update', 'Plentymarket\Controllers\Api\BasketController@update');
		$router->get('/api/wishlist/create/{itemId}', 'Plentymarket\Controllers\Api\WishlistController@create');
		$router->get('/api/wishlist/delete/{itemId}', 'Plentymarket\Controllers\Api\WishlistController@delete');
		$router->get('/api/wishlist/has/{itemId}', 'Plentymarket\Controllers\Api\WishlistController@has');
		$router->get('/api/wishlist/num', 'Plentymarket\Controllers\Api\WishlistController@num');
		$router->get('/api/address/create', 'Plentymarket\Controllers\Api\AddressController@create');
		$router->post('/api/payment/paypal', 'Plentymarket\Controllers\Api\PaymentController@paypal');
		$router->get('/api/order/create', 'Plentymarket\Controllers\Api\OrderController@create');
		$router->get('/api/index/product/{product_id}', 'Plentymarket\Controllers\Api\IndexController@product');

		//页面声明
		$router->get('/', 'Plentymarket\Controllers\Web\IndexController@index');
		$router->get('/index/about', 'Plentymarket\Controllers\Web\IndexController@about');
		$router->get('/index/contact', 'Plentymarket\Controllers\Web\IndexController@contact');
		$router->get('/index/faq', 'Plentymarket\Controllers\Web\IndexController@faq');
		$router->get('/index/login_register', 'Plentymarket\Controllers\Web\IndexController@login_register');
		$router->get('/index/product_list_category/{category_id}', 'Plentymarket\Controllers\Web\IndexController@product_list_category');
		$router->get('/index/product/{product_id}', 'Plentymarket\Controllers\Web\IndexController@product');
		$router->get('/index/blog_list', 'Plentymarket\Controllers\Web\IndexController@blog_list');
		$router->get('/index/blog/{blog_id}', 'Plentymarket\Controllers\Web\IndexController@blog');

		$router->get('/account/index', 'Plentymarket\Controllers\Web\AccountController@index');
		$router->get('/account/cart', 'Plentymarket\Controllers\Web\AccountController@cart');
		$router->get('/account/checkout', 'Plentymarket\Controllers\Web\AccountController@checkout');
		$router->get('/account/wishlist', 'Plentymarket\Controllers\Web\AccountController@wishlist');
	}

}
