<?php

namespace Plentymarket\Services;

/**
 * Class HttpService
 * @package Plentymarket\Services
 */
class HttpService
{
	/**
	 * HttpService constructor.
	 */
	function __construct ()
	{
	}

	/**
	 * get请求
	 * @param string $url
	 * @param array $data
	 * @param array $header
	 * @return bool|string
	 */
	function get (string $url, array $data = [], array $header = [])
	{
		$query = '';
		if (!empty($data)) {
			$query = '?' . http_build_query($data);
		}
		$curl = curl_init($url . $query);
		curl_setopt($curl, [
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_HTTPHEADER => $header
		]);
		$output = curl_exec($curl);
		curl_close($curl);
		return $output;
	}

	/**
	 * @param string $url
	 * @param array $data
	 * @param array $header
	 * @return bool|string
	 */
	function post (string $url, array $data = [], array $header = [])
	{
		$curl = curl_init($url);
		curl_setopt($curl, [
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_POST => 1,
			CURLOPT_POSTFIELDS => $data,
			CURLOPT_HTTPHEADER => $header
		]);
		$output = curl_exec($curl);
		curl_close($curl);
		return $output;
	}

}
