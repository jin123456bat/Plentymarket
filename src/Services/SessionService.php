<?php
namespace Plentymarket\Services;
use Plenty\Modules\Frontend\Events\FrontendLanguageChanged;
use Plenty\Modules\Frontend\Session\Storage\Contracts\FrontendSessionStorageFactoryContract;
use Plenty\Plugin\Events\Dispatcher;
use Plenty\Plugin\Http\Request;
use Plentymarket\Helper\Utils;

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

	private $language;

	/**
	 * SessionService constructor.
	 * @param FrontendSessionStorageFactoryContract $frontendSessionStorageFactoryContract
	 */
	public function __construct (FrontendSessionStorageFactoryContract $frontendSessionStorageFactoryContract,Dispatcher $eventDispatcher)
	{
		$this->frontendSessionStorageFactoryContract = $frontendSessionStorageFactoryContract;
		$eventDispatcher->listen(FrontendLanguageChanged::class, function(FrontendLanguageChanged $event)
		{
			$this->language = $event->getLanguage();
		});
	}

	/**
	 * Get the language from session
	 * @return string
	 */
	public function getLang()
	{
		if ( is_null($this->language) )
		{
			$this->language = $this->frontendSessionStorageFactoryContract->getLocaleSettings()->language;

//			if(is_null($this->language) || !strlen($this->language))
//			{
//				$request = pluginApp(Request::class);
//				$splittedURL = explode('/', $request->get('plentyMarkets'));
//				if(strpos(end($splittedURL), '.') === false && in_array($splittedURL[0], Utils::getLanguageList()))
//				{
//					$this->language = $splittedURL[0];
//				}
//			}

			if(is_null($this->language) || !strlen($this->language))
			{
				$this->language = Utils::getDefaultLang();
			}
		}

		return $this->language;
	}

	/**
	 * 获取session
	 * @param string $key
	 * @param $value
	 */
	public function set(string $key, $value)
	{
		$this->frontendSessionStorageFactoryContract->getPlugin()->setValue($key, $value);
	}

	/**
	 * 设置session
	 * @param string $key
	 * @return mixed
	 */
	public function get(string $key)
	{
		return $this->frontendSessionStorageFactoryContract->getPlugin()->getValue($key);
	}

	/**
	 * 貌似是当前登录的用户信息
	 * @return \Plenty\Modules\Frontend\Session\Storage\Models\Customer
	 */
	public function getCustomer()
	{
		return $this->frontendSessionStorageFactoryContract->getCustomer();
	}
}
