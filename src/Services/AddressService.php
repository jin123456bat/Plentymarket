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
	 * 配送地址
	 */
	const Address_Delivery = 2;

	/**
	 * 账单地址
	 */
	const Address_Invoice = 1;

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
	 * @return Address
	 */
	function create (array $data): Address
	{
		$contactId = pluginApp(AccountService::class)->getContactId();
		if (!empty($contactId)) {
			//添加配送地址
			$address = $this->contactAddressRepositoryContract->createAddress($data, $contactId, self::Address_Delivery);

			//把配送地址关联到账单地址上
			$this->contactAddressRepositoryContract->addAddress($address->id, $contactId, self::Address_Invoice);

			//设置默认地址
			$this->contactAddressRepositoryContract->setPrimaryAddress($address->id, $contactId, self::Address_Delivery);
			$this->contactAddressRepositoryContract->setPrimaryAddress($address->id, $contactId, self::Address_Invoice);

			return $address;
		}
		return null;
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
