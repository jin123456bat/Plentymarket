<?php

namespace Plentymarket\Helper;

use Plenty\Plugin\Application;
use Plentymarket\Services\ConfigService;

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
        $app = pluginApp(Application::class);
        return (int) $app->getWebstoreId();
    }
}
