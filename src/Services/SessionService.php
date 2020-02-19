<?php

namespace Plentymarket\Services;

use Plenty\Modules\Frontend\Contracts\Checkout;
use Plenty\Modules\Frontend\Events\FrontendLanguageChanged;
use Plenty\Modules\Frontend\Session\Storage\Contracts\FrontendSessionStorageFactoryContract;
use Plenty\Modules\Frontend\Session\Storage\Models\Customer;
use Plenty\Plugin\Events\Dispatcher;

/**
 * Class SessionService
 * @package Plentymarket\Services
 */
class SessionService
{
	/**
	 * @var FrontendSessionStorageFactoryContract
	 */
	private $frontendSessionStorageFactoryContract;

	/**
	 * @var string
	 */
	private $language;

	/**
	 * SessionService constructor.
	 * @param FrontendSessionStorageFactoryContract $frontendSessionStorageFactoryContract
	 */
	public function __construct (FrontendSessionStorageFactoryContract $frontendSessionStorageFactoryContract, Dispatcher $eventDispatcher)
	{
		$this->frontendSessionStorageFactoryContract = $frontendSessionStorageFactoryContract;
		$eventDispatcher->listen(FrontendLanguageChanged::class, function (FrontendLanguageChanged $event) {
			//设置当前语言
			$this->language = $event->getLanguage();
		});
	}

	/**
	 * 获取当前语言
	 * @return string
	 */
	public function getLang ()
	{
		if (is_null($this->language)) {
			$this->language = $this->frontendSessionStorageFactoryContract->getLocaleSettings()->language;
			if (is_null($this->language) || !strlen($this->language)) {
				$this->language = pluginApp(ConfigService::class)->getWebsiteConfig('defaultLanguage');
			}
		}

		return $this->language;
	}

	/**
	 * 获取当前的货币类型
	 * @return string
	 */
	public function getCurrency (): string
	{
		$currency = (string)$this->get('currency');
		if (empty($currency)) {
			$currency = 'EUR';
		}

		/** @var Checkout $checkout */
		$checkout = pluginApp(Checkout::class);
		$checkout->setCurrency($currency);
		return $currency;
	}

	/**
	 * 获取session
	 * @param string $key
	 * @param $value
	 */
	public function set (string $key, $value)
	{
		$this->frontendSessionStorageFactoryContract->getPlugin()->setValue($key, $value);
	}

	/**
	 * 设置session
	 * @param string $key
	 * @return mixed
	 */
	public function get (string $key)
	{
		return $this->frontendSessionStorageFactoryContract->getPlugin()->getValue($key);
	}

	/**
	 * @return Customer
	 */
	public function getCustomer (): Customer
	{
		return $this->frontendSessionStorageFactoryContract->getCustomer();
	}
}
