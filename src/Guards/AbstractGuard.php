<?php

namespace Plentymarket\Guards;

use Plenty\Plugin\Log\Loggable;
use Plentymarket\Services\ConfigService;

/**
 * Class AbstractGuard
 * @package Plentymarket\Guards
 */
abstract class AbstractGuard
{
	use Loggable;

	/**
	 * Redirect to given URI if expected value equals return value of AbstractGuard::assert()
	 * @param mixed $expected The value to compare with return value of AbstractGuard::asssert()
	 * @param string $redirectUri The URI to redirect to
	 */
	public function assertOrRedirect ($expected, string $redirectUri)
	{
		if ($this->assert() !== $expected) {
			exit('立即跳转...:' . self::getUrl());
			self::redirect($redirectUri, ["backlink" => self::getUrl()]);
		}
	}

	/**
	 * Returned value will be compared with asserted value to decide if it should redirect.
	 * @return mixed
	 */
	protected abstract function assert ();

	// TODO move to more general class

	/**
	 * Transform a given URI to an URL by prepending used protocol and server name.
	 * Will return current URL if $uri is null
	 * @param string|null $uri
	 * @return string
	 */
	public static function getUrl (string $uri = null)
	{
		if ($uri === null) {
			$uri = $_SERVER['REQUEST_URI'];
		}

		$domain = pluginApp(ConfigService::class)->getWebsiteConfig('domainSsl');

		return $domain . $uri;
	}

	/**
	 * Redirect to a given URI. Appends params as query string.
	 * @param string $uri The URI to redirect to.
	 * @param array $params A map of params to append to URI as query string.
	 */
	public static function redirect (string $uri, array $params = [])
	{
		$url = self::getUrl($uri);

		$queryParams = [];
		foreach ($params as $key => $value) {
			$param = rawurlencode($key) . "=" . rawurlencode($value);
			array_push($queryParams, $param);
		}

		$query = "";
		if (count($queryParams) > 0) {
			$query = "?" . implode("&", $queryParams);
		}

		header('Location: ' . $url . $query, true, 302);
		exit;
	}

}
