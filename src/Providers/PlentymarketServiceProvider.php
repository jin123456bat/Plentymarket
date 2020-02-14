<?php
namespace Plentymarket\Providers;

use Plenty\Plugin\ServiceProvider;
use Plentymarket\Services\AccountService;
use Plentymarket\Services\CategoryService;
use Plentymarket\Services\ConfigService;
use Plentymarket\Services\SessionService;

/**
 * Class PlentymarketServiceProvider
 * @package Plentymarket\Providers
 */
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
			CategoryService::class,
			ConfigService::class,
			SessionService::class,
		]);
	}

	/**
	 * @param $classes
	 */
	private function registerSingletons ($classes)
	{
		foreach ($classes as $class) {
			$this->getApplication()->singleton($class);
		}
	}
}
