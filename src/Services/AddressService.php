<?php

namespace Plentymarket\Services;

use Plenty\Modules\Account\Address\Models\Address;
use Plenty\Modules\Account\Contact\Contracts\ContactAddressRepositoryContract;

/**
 * Class AddressService
 * @package Plentymarket\Services
 */
class AddressService
{
	/**
	 * @var ContactAddressRepositoryContract
	 */
	private $contactAddressRepositoryContract;

	/**
	 * AddressService constructor.
	 * @param ContactAddressRepositoryContract $contactAddressRepositoryContract
	 */
	function __construct (ContactAddressRepositoryContract $contactAddressRepositoryContract)
	{
		$this->contactAddressRepositoryContract = $contactAddressRepositoryContract;
	}

	/**
	 * 添加地址
	 * @param array $data 地址数据
	 * @param int $contactId 用户
	 * @param int $typeId 地址类型 Invoice address = 1 Delivery address = 2
	 * @return Address
	 */
	function create (array $data, int $contactId, int $typeId): Address
	{
		$this->contactAddressRepositoryContract->createAddress($data, $contactId, $typeId);
	}

	/**
	 * 更新地址信息
	 * @param int $addressId
	 * @param int $contactId
	 * @param int $typeId
	 * @param array $data
	 * @return Address
	 */
	function update (int $addressId, int $contactId, int $typeId, array $data): Address
	{
		return $this->contactAddressRepositoryContract->updateAddress($data, $addressId, $contactId, $typeId);
	}

	/**
	 * 删除地址
	 * @param int $addressId
	 * @param int $contactId
	 * @param int $typeId
	 * @return bool
	 */
	function delete (int $addressId, int $contactId, int $typeId): bool
	{
		return $this->contactAddressRepositoryContract->deleteAddress($addressId, $contactId, $typeId);
	}

	/**
	 * 获取所有地址列表
	 * @param int $contactId
	 * @param int|null $typeId
	 * @return array
	 */
	function getAll (int $contactId, int $typeId = null): array
	{
		return $this->contactAddressRepositoryContract->getAddresses($contactId, $typeId);
	}
}
