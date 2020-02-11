<?php

namespace Plentymarket\Helper;

use Plenty\Plugin\Application;

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
		/** @var Application $app */
		$app = pluginApp(Application::class);
		return (int) $app->getPlentyId();
	}

	/**
	 * 获取网站ID
	 * @return int
	 */
	public static function getWebstoreId()
    {
        /** @var Application $app */
        $app = pluginApp(Application::class);
        return (int) $app->getWebstoreId();
    }

	/**
	 * 获取支持的语言列表
	 * @return mixed
	 */
	public static function getLanguageList()
	{
		$webstoreConfigService = pluginApp(WebstoreConfigurationService::class);
		return $webstoreConfigService->getActiveLanguageList();
	}
}
