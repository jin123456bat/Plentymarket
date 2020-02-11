<?php
namespace Plentymarket\Services;
use Plenty\Modules\Account\Contact\Contracts\ContactRepositoryContract;
use Plenty\Modules\Account\Contact\Models\Contact;
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

	/**
	 * AccountService constructor.
	 * @param ContactRepositoryContract $contactRepositoryContract
	 */
	public function __construct (ContactRepositoryContract $contactRepositoryContract)
	{
		$this->contactRepositoryContract = $contactRepositoryContract;
	}

	/**
	 * 添加账户
	 */
	function register($email,$password):Contact
	{
		$sessionService = pluginApp(SessionService::class);
		try{
			$contact = $this->contactRepositoryContract->createContact([
				'checkForExistingEmail' => true,
				'plentyId' => Utils::getPlentyId(),
				'lang' => $sessionService->getLang(),
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
			return null;
		}


		if ($contact instanceof Contact && $contact->id > 0) {
			$authenticationService = pluginApp(AuthenticationService::class);
			$authenticationService->loginWithContactId($contact->id, $password);

			$params = [
				'contactId' => $contact->id,
				'clientId' => Utils::getWebstoreId(),
				'password' => $password,
				'language' => $this->sessionStorage->getLang()
			];

			$this->sendMail(AutomaticEmailTemplate::CONTACT_REGISTRATION, AutomaticEmailContact::class, $params);
		}

		return $contact;
	}
}
