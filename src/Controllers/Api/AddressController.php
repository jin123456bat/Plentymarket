<?php

namespace Plentymarket\Controllers\Api;

use Plenty\Plugin\Http\Response;
use Plentymarket\Controllers\BaseApiController;
use Plentymarket\Services\AddressService;

class AddressController extends BaseApiController
{
	function create (): Response
	{
		$address = pluginApp(AddressService::class);
		$address = $address->create([

		]);

		return $this->success($address);
	}

	function delete (): Response
	{
		$address = pluginApp(AddressService::class);
		$address->delete();
		return $this->success();
	}
}
