<?php
namespace Plentymarket\Providers;

use Plenty\Plugin\RouteServiceProvider;
use Plenty\Plugin\Routing\Router;

/**
 * Class HelloWorldRouteServiceProvider
 * @package HelloWorld\Providers
 */
class PlentymarketRouteServiceProvider extends RouteServiceProvider
{
	/**
	 * @param Router $router
	 */
	public function map(Router $router)
	{
	    $router->get('/','Plentymarket\Controllers\IndexController@index');
		$router->get('hello', 'Plentymarket\Controllers\ContentController@sayHello');
	}

}
