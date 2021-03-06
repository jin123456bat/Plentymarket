<?php

namespace Plentymarket\Services;

use Exception;
use Plenty\Modules\Account\Contact\Contracts\ContactClassRepositoryContract;
use Plenty\Modules\Account\Contact\Contracts\ContactRepositoryContract;
use Plenty\Modules\Account\Contact\Models\Contact;
use Plenty\Modules\Authentication\Contracts\ContactAuthenticationRepositoryContract;
use Plenty\Modules\Authorization\Services\AuthHelper;
use Plenty\Modules\Frontend\Session\Storage\Models\Customer;
use Plenty\Modules\Helper\AutomaticEmail\Models\AutomaticEmailContact;
use Plenty\Modules\Helper\AutomaticEmail\Models\AutomaticEmailTemplate;
use Plentymarket\Extensions\SendMail;
use Plentymarket\Helper\Utils;

/**
 * Class AccountService
 * @package Plentymarket\Services
 */
class AccountService
{
	use SendMail;

	/**
	 * @var ContactRepositoryContract
	 */
	private $contactRepositoryContract;

	/*
	 * @var ContactAuthenticationRepositoryContract
	 */
	/**
	 * @var ContactAuthenticationRepositoryContract
	 */
	private $contactAuthenticationRepositoryContract;

	/**
	 * @var SessionService
	 */
	private $session;

	/**
	 * AccountService constructor.
	 * @param ContactRepositoryContract $contactRepositoryContract
	 * @param ContactAuthenticationRepositoryContract $contactAuthenticationRepositoryContract
	 */
	public function __construct (ContactRepositoryContract $contactRepositoryContract, ContactAuthenticationRepositoryContract $contactAuthenticationRepositoryContract)
	{
		$this->contactRepositoryContract = $contactRepositoryContract;
		$this->contactAuthenticationRepositoryContract = $contactAuthenticationRepositoryContract;

		$this->session = pluginApp(SessionService::class);
	}

	/**
	 * 登录
	 * @param string $email
	 * @param string $password
	 */
	function login (string $email, string $password)
	{
		$this->contactAuthenticationRepositoryContract->authenticateWithContactEmail($email, $password);
	}

	/**
	 * 注销登录
	 */
	function logout ()
	{
		$this->contactAuthenticationRepositoryContract->logout();
	}

	/**
	 * 添加账户
	 * @param string $email
	 * @param string $password
	 * @return bool
	 */
	function register (string $email, string $password): bool
	{
		try {
			$contact = $this->contactRepositoryContract->createContact([
				'checkForExistingEmail' => true,
				'plentyId' => Utils::getPlentyId(),
				'lang' => $this->session->getLang(),
				//下面这几个属性我也不知道是干嘛的，从plugin-ceres的原生代码中发现的
				'referrerId' => 1,
				'typeId' => 1,
				'options' => [
					'typeId' => [
						'typeId' => 2,
						'subTypeId' => 4,
						'value' => $email,
						'priority' => 0,
					]
				],
				'password' => $password
			]);
		} catch (Exception $e) {
			return false;
		}

		if ($contact instanceof Contact && $contact->id > 0) {
			//注册成功后立即登录
			$this->login($email, $password);

			//发送注册邮件
			$params = [
				'contactId' => $contact->id,
				'clientId' => Utils::getWebstoreId(),
				'password' => $password,
				'language' => $this->session->getLang()
			];

			$this->sendMail(AutomaticEmailTemplate::CONTACT_REGISTRATION, AutomaticEmailContact::class, $params);
		}

		return true;
	}

	/**
	 * @param int $contactClassId
	 * @return array|null
	 */
	public function getContactClassData ($contactClassId)
	{
		/** @var ContactClassRepositoryContract $contactClassRepo */
		$contactClassRepo = pluginApp(ContactClassRepositoryContract::class);

		/** @var AuthHelper $authHelper */
		$authHelper = pluginApp(AuthHelper::class);

		return $authHelper->processUnguarded(
			function () use ($contactClassRepo, $contactClassId) {
				return $contactClassRepo->findContactClassDataById($contactClassId);
			}
		);
	}

	/**
	 * @return int
	 */
	public function getContactClassId (): int
	{
		$contact = $this->getContact();
		if ($contact !== null && $contact->classId !== null) {
			return $contact->classId;
		} else {
			return pluginApp(ConfigService::class)->getWebsiteConfig('defaultCustomerClassId') ?? 0;
		}
	}

	/**
	 * 获取当前登录的用户
	 * @return null|Contact  null为未登录
	 */
	public function getContact ()
	{
		if ($this->getContactId() > 0) {
			return $this->contactRepositoryContract->findContactById($this->getContactId());
		}
		return null;
	}

	/**
	 * 获取当前登录用户的id
	 * @return int  0为未登录
	 */
	public function getContactId (): int
	{
		/** @var \Plenty\Modules\Frontend\Services\AccountService $accountService */
		$accountService = pluginApp(\Plenty\Modules\Frontend\Services\AccountService::class);
		return $accountService->getAccountContactId();
	}

	/**
	 * @return bool
	 */
	public function showNetPrices (): bool
	{
		$customerShowNet = false;
		/** @var Customer $customer */
		$customer = $sessionService = pluginApp(SessionService::class)->getCustomer();
		if ($customer !== null) {
			$customerShowNet = $customer->showNetPrice;
		}

		if ($customerShowNet) {
			return true;
		}

		$contactClassShowNet = false;
		$contactClassId = $this->getContactClassId();
		if ($contactClassId !== null) {
			$contactClass = $this->getContactClassData($contactClassId);
			if ($contactClass !== null) {
				$contactClassShowNet = $contactClass['showNetPrice'];
			}
		}

		return $contactClassShowNet;
	}
}
