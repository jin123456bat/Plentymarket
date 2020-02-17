<?php

namespace Plentymarket\Helper;

use Plenty\Plugin\Application;
use Plentymarket\Services\ConfigService;
use Plentymarket\Services\SessionService;

/**
 * Class Utils
 * @package Plentymarket\Helper
 */
class Utils
{
	/**
	 * 获取plentymarket平台ID
	 * @return int
	 */
	public static function getPlentyId()
	{
		$app = pluginApp(Application::class);
		return (int) $app->getPlentyId();
	}

	/**
	 * 获取网站ID
	 * @return int
	 */
	public static function getWebstoreId ()
	{
		$app = pluginApp(Application::class);
		return (int)$app->getWebstoreId();
	}

	/**
	 * 获取语言
	 * @return mixed
	 */
	public static function getLang ()
	{
		$sessionStorage = pluginApp(SessionService::class);
		return $sessionStorage->getLang();
	}
}
