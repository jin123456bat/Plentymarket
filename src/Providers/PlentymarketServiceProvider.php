<?php
namespace Plentymarket\Providers;

use Plenty\Plugin\ServiceProvider;
use Plentymarket\Services\AccountService;
use Plentymarket\Services\ConfigService;
use Plentymarket\Services\SessionService;

class PlentymarketServiceProvider extends ServiceProvider
{

	/**
	 * Register the service provider.
	 */
	public function register ()
	{
		$this->getApplication()->register(PlentymarketRouteServiceProvider::class);

		$this->registerSingletons([
			AccountService::class,
			ConfigService::class,
			SessionService::class,
		]);
	}

	private function registerSingletons ($classes)
	{
		foreach ($classes as $class) {
			$this->getApplication()->singleton($class);
		}
	}
}
