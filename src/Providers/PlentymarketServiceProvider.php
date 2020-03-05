<?php

namespace Plentymarket\Providers;

use Plenty\Modules\Cron\Services\CronContainer;
use Plenty\Plugin\Events\Dispatcher;
use Plenty\Plugin\ServiceProvider;
use Plenty\Plugin\Templates\Twig;
use Plentymarket\Extensions\TwigServiceProvider;
use Plentymarket\Middlewares\AuthMiddleware;
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
		$this->addGlobalMiddleware(AuthMiddleware::class);

		$this->getApplication()->register(PlentymarketRouteServiceProvider::class);

		$this->registerSingletons([
			AccountService::class,
			CategoryService::class,
			ConfigService::class,
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
