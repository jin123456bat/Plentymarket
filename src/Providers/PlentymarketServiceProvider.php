<?php

namespace Plentymarket\Providers;

use Plenty\Modules\Cron\Services\CronContainer;
use Plenty\Plugin\Events\Dispatcher;
use Plenty\Plugin\ServiceProvider;
use Plenty\Plugin\Templates\Twig;
use Plentymarket\Extensions\TwigServiceProvider;
use Plentymarket\Services\AccountService;
use Plentymarket\Services\AddressService;
use Plentymarket\Services\BasketService;
use Plentymarket\Services\BlogService;
use Plentymarket\Services\CategoryService;
use Plentymarket\Services\CheckoutService;
use Plentymarket\Services\ConfigService;
use Plentymarket\Services\CountryService;
use Plentymarket\Services\CurrencyService;
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
		$this->addGlobalMiddleware(AuthMiddleware::class);

		$this->getApplication()->register(PlentymarketRouteServiceProvider::class);

		$this->registerSingletons([
			AccountService::class,
			AddressService::class,
			BasketService::class,
			BlogService::class,
			CategoryService::class,
			CheckoutService::class,
			ConfigService::class,
			CountryService::class,
			CurrencyService::class,
			SessionService::class,
		]);
	}

	public function boot (Twig $twig, Dispatcher $dispatcher, CronContainer $cronContainer)
	{
		$twig->addExtension(TwigServiceProvider::class);
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
