<?php
namespace Plentymarket\Services;
use Plenty\Modules\Account\Contact\Contracts\ContactRepositoryContract;
use Plenty\Modules\Account\Contact\Models\Contact;
use Plenty\Modules\Authentication\Contracts\ContactAuthenticationRepositoryContract;
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
	 * @param ContactAuthenticationRepositoryContract
	 */
	public function __construct (ContactRepositoryContract $contactRepositoryContract,ContactAuthenticationRepositoryContract $contactAuthenticationRepositoryContract)
	{
		$this->contactRepositoryContract = $contactRepositoryContract;
		$this->contactAuthenticationRepositoryContract = $contactAuthenticationRepositoryContract;

		$this->session = pluginApp(SessionService::class);
	}

	/**
	 * 登录
	 * @param $email
	 * @param $password
	 */
	function login($email, $password)
	{
		$this->contactAuthenticationRepositoryContract->authenticateWithContactEmail($email,$password);
	}

	/**
	 * 添加账户
	 */
	function register($email,$password):Contact
	{
		try{
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
				'password' =>$password
			]);
		}
		catch(\Exception $e)
		{
			return false;
		}


		if ($contact instanceof Contact && $contact->id > 0) {
			//注册成功后立即登录
			$this->login($email,$password);

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
}
