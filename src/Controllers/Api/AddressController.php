<?php

namespace Plentymarket\Controllers\Api;

use Plenty\Plugin\Http\Response;
use Plentymarket\Controllers\BaseApiController;
use Plentymarket\Services\AddressService;

/**
 * Class AddressController
 * @package Plentymarket\Controllers\Api
 */
class AddressController extends BaseApiController
{
	/**
	 * 添加地址
	 * @return Response
	 */
	function create (): Response
	{
		$address = pluginApp(AddressService::class);
		$address = $address->create([

		]);

		return $this->success($address);
	}

	/**
	 * @return Response
	 */
	function delete (): Response
	{
		$address = pluginApp(AddressService::class);
		return $this->success([]);
	}
}
