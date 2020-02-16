<?php

namespace Plentymarket\Providers;

use Plenty\Plugin\RouteServiceProvider;
use Plenty\Plugin\Routing\ApiRouter;
use Plenty\Plugin\Routing\Router;

/**
 * Class PlentymarketRouteServiceProvider
 * @package Plentymarket\Providers
 */
class PlentymarketRouteServiceProvider extends RouteServiceProvider
{
	/**
	 * @param Router $router
	 * @param ApiRouter $api
	 */
	public function map (Router $router, ApiRouter $api)
	{
		//接口声明
//		$api->version(['v1'], ['namespace' => 'Plentymarket\Controllers\Api'], function (ApiRouter $api) {
//			$api->post('api/index/login', 'IndexController@login');
//			$api->post('api/index/register', 'IndexController@register');
//		});

		//测试api
		$router->get('/api/index/category', 'Plentymarket\Controllers\Api\IndexController@category');
		$router->get('/api/index/itemset', 'Plentymarket\Controllers\Api\IndexController@itemset');
		$router->get('/api/index/warehouse', 'Plentymarket\Controllers\Api\IndexController@warehouse');
		$router->get('/api/index/stock', 'Plentymarket\Controllers\Api\IndexController@stock');
		$router->get('/api/index/warehousestock', 'Plentymarket\Controllers\Api\IndexController@warehousestock');
		$router->get('/api/index/blog', 'Plentymarket\Controllers\Api\IndexController@blog');
		$router->get('/api/index/item', 'Plentymarket\Controllers\Api\IndexController@item');

		//生产api
		$router->get('/api/index/register', 'Plentymarket\Controllers\Api\IndexController@register');
		$router->get('/api/index/login', 'Plentymarket\Controllers\Api\IndexController@login');

		//页面声明
		$router->get('/', 'Plentymarket\Controllers\Web\IndexController@index');
		$router->get('/index/about', 'Plentymarket\Controllers\Web\IndexController@about');
		$router->get('/index/contact', 'Plentymarket\Controllers\Web\IndexController@contact');
		$router->get('/index/faq', 'Plentymarket\Controllers\Web\IndexController@faq');
		$router->get('/index/login_register', 'Plentymarket\Controllers\Web\IndexController@login_register');
		$router->get('/index/product_list_category/{category_id}', 'Plentymarket\Controllers\Web\IndexController@product_list_category');
		$router->get('/index/blog_list', 'Plentymarket\Controllers\Web\IndexController@blog_list');
		$router->get('/index/blog/{blog_id}', 'Plentymarket\Controllers\Web\IndexController@blog');

		$router->get('/account/index', 'Plentymarket\Controllers\Web\AccountController@index');
		$router->get('/account/cart', 'Plentymarket\Controllers\Web\AccountController@cart');
		$router->get('/account/checkout', 'Plentymarket\Controllers\Web\AccountController@checkout');
		$router->get('/account/wishlist', 'Plentymarket\Controllers\Web\AccountController@wishlist');
	}

}
