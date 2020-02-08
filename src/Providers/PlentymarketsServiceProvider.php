<?php
namespace Plentymarkets\Providers;

use Plenty\Plugin\ServiceProvider;

/**
 * Class HelloWorldServiceProvider
 * @package HelloWorld\Providers
 */
class PlentymarketsServiceProvider extends ServiceProvider
{

	/**
	 * Register the service provider.
	 */
	public function register()
	{
		$this->getApplication()->register(PlentymarketsRouteServiceProvider::class);
	}
}
