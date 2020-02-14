<?php

namespace Plentymarket\Helper;

use Plenty\Plugin\ConfigRepository;

/**
 * Class RouteConfig
 * @package Plentymarket\Helper
 */
class RouteConfig
{
	/**
	 *
	 */
	const HOME = "home";

	/**
	 * @var array
	 */
	private static $overrides = [];

	/**
	 * @param $route
	 * @return int|mixed
	 */
	public static function getCategoryId ($route)
	{
		if (array_key_exists($route, self::$overrides)) {
			return self::$overrides[$route];
		}
		$config = pluginApp(ConfigRepository::class);
		return (int)$config->get('Plentymarket.routing.category_' . $route, 0);
	}
}
