<?php

namespace Plentymarket\Controllers\Api;

use Plenty\Exceptions\ValidationException;
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
		try {
			$address = $address->create([
				'name2' => $this->request->input('first_name'),
				'name3' => $this->request->input('second_name'),
				'countryId' => $this->request->input('country'),
				'address1' => $this->request->input('address1'),
				'address2' => $this->request->input('address2'),
				'postalCode' => $this->request->input('zipcode'),
				'town' => $this->request->input('town'),
				'stateId' => $this->request->input('state'),//浙江的ID
				'companyName' => $this->request->input('company_name'),
				'email' => $this->request->input('email'),
				'phone' => $this->request->input('phone')
			]);
		} catch (ValidationException $e) {
			return $this->error($e->getMessage());
		} catch (\Throwable $e) {
			return $this->exception($e);
		}

		return $this->success($address->toArray());
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
