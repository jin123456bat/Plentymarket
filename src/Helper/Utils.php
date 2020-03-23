<?php

namespace Plentymarket\Helper;

use Plenty\Plugin\Application;
use Plentymarket\Services\SessionService;

/**
 * Class Utils
 * @package Plentymarket\Helper
 */
class Utils
{
	/**
	 * 是否是管理员预览模式
	 * @return mixed
	 */
	public static function isAdminPreview ()
	{
		$app = pluginApp(Application::class);
		return $app->isAdminPreview();
	}

	/**
	 * 获取当前选择的货币类型
	 * @return mixed
	 */
	public static function getCurrency ()
	{
		$sessionStorage = pluginApp(SessionService::class);
		return $sessionStorage->getCurrency();
	}

	/**
	 * 获取plentymarket平台ID
	 * @return int
	 */
	public static function getPlentyId ()
	{
		$app = pluginApp(Application::class);
		return (int)$app->getPlentyId();
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
