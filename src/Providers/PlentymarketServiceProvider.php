<?php
namespace Plentymarket\Providers;

use Plenty\Plugin\ServiceProvider;

class PlentymarketServiceProvider extends ServiceProvider
{

	/**
	 * Register the service provider.
	 */
	public function register()
	{
		$this->getApplication()->register(PlentymarketRouteServiceProvider::class);
	}
}
