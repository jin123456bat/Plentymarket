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
	function create (array $data): ?Address
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
	 * @param array $data
	 * @return Address|null
	 */
	function update (int $addressId, array $data): ?Address
	{
		$contactId = pluginApp(AccountService::class)->getContactId();
		if (!empty($contactId)) {
			$address = $this->contactAddressRepositoryContract->updateAddress($data, $addressId, $contactId, self::Address_Delivery);
			if ($address instanceof Address) {
				$this->contactAddressRepositoryContract->updateAddress($data, $addressId, $contactId, self::Address_Invoice);
				return $address;
			}
			return null;
		}
		return null;
	}

	/**
	 * 删除地址
	 * @param int $addressId
	 * @return bool
	 */
	function delete (int $addressId): bool
	{
		$contactId = pluginApp(AccountService::class)->getContactId();
		if (!empty($contactId)) {
			$result1 = $this->contactAddressRepositoryContract->deleteAddress($addressId, $contactId, self::Address_Delivery);
			$result2 = $this->contactAddressRepositoryContract->deleteAddress($addressId, $contactId, self::Address_Invoice);
			return $result1 && $result2;
		}
		return false;
	}

	/**
	 * 获取所有地址列表
	 * @return array
	 */
	function getAll (): array
	{
		$contactId = pluginApp(AccountService::class)->getContactId();
		if (!empty($contactId)) {
			$result = $this->contactAddressRepositoryContract->getAddresses($contactId, self::Address_Delivery);
			return json_decode(json_encode($result), true);
		}
		return [];
	}
}
