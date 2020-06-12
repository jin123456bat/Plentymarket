<?php

namespace Plentymarket\Providers;

use Plenty\Modules\Cron\Services\CronContainer;
use Plenty\Plugin\Events\Dispatcher;
use Plenty\Plugin\ServiceProvider;
use Plenty\Plugin\Templates\Twig;
use Plentymarket\Extensions\TwigServiceProvider;
use Plentymarket\Middlewares\AuthMiddleware;
use Plentymarket\Services\AccountService;
use Plentymarket\Services\AddressService;
use Plentymarket\Services\BasketService;
use Plentymarket\Services\BlogService;
use Plentymarket\Services\CategoryService;
use Plentymarket\Services\CheckoutService;
use Plentymarket\Services\CommentService;
use Plentymarket\Services\CommonService;
use Plentymarket\Services\ConfigService;
use Plentymarket\Services\CountryService;
use Plentymarket\Services\CurrencyService;
use Plentymarket\Services\FeedbackService;
use Plentymarket\Services\HomeService;
use Plentymarket\Services\HttpService;
use Plentymarket\Services\ImageService;
use Plentymarket\Services\ItemListService;
use Plentymarket\Services\ItemService;
use Plentymarket\Services\ItemSetService;
use Plentymarket\Services\ManufacturerService;
use Plentymarket\Services\OrderService;
use Plentymarket\Services\PaymentMethodService;
use Plentymarket\Services\PayPalService;
use Plentymarket\Services\PriceDetectService;
use Plentymarket\Services\SessionService;
use Plentymarket\Services\StockService;
use Plentymarket\Services\TranslateService;
use Plentymarket\Services\UnitService;
use Plentymarket\Services\WarehouseService;

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
			CommentService::class,
			ConfigService::class,
			CountryService::class,
			CurrencyService::class,
			SessionService::class,
			FeedbackService::class,
			HttpService::class,
			ImageService::class,
			ItemListService::class,
			ItemService::class,
			ItemSetService::class,
			ManufacturerService::class,
			OrderService::class,
			PaymentMethodService::class,
			PayPalService::class,
			PriceDetectService::class,
			SessionService::class,
			StockService::class,
			UnitService::class,
			WarehouseService::class,
			CommonService::class,
			HomeService::class,
			TranslateService::class,
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
