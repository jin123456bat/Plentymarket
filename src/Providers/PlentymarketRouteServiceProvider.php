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
	    $router->get('/','Plentymarket\Controllers\Web\IndexController@index');

	    $router->post('/api/login','Plentymarket\Controller\Api\IndexController@login');
		$router->post('/api/register','Plentymarket\Controller\Api\IndexController@register');
	}

}
