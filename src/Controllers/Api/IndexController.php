<?php

namespace Plentymarket\Controllers\Api;

use Exception;
use Plenty\Modules\Account\Contact\Contracts\ContactAddressRepositoryContract;
use Plenty\Plugin\Http\Request;
use Plenty\Plugin\Http\Response;
use Plentymarket\Controllers\BaseApiController;
use Plentymarket\Services\AccountService;
use Plentymarket\Services\BlogService;
use Plentymarket\Services\CategoryService;
use Plentymarket\Services\CountryService;
use Plentymarket\Services\ItemListService;
use Plentymarket\Services\ItemService;
use Plentymarket\Services\ItemSetService;
use Plentymarket\Services\StockService;
use Plentymarket\Services\WarehouseService;
use Throwable;

/**
 * Class IndexController
 * @package Plentymarket\Controllers\Api
 */
class IndexController extends BaseApiController
{
	/**
	 * @var AccountService
	 */
	private $accountService;

	/**
	 * IndexController constructor.
	 * @param Request $request
	 * @param Response $response
	 * @param AccountService $accountService
	 */
	function __construct (Request $request, Response $response, AccountService $accountService)
	{
		parent::__construct($request, $response);
		$this->accountService = $accountService;
	}

	/**
	 * 用户登录接口
	 * @return Response
	 */
	public function login (): Response
	{
		$email = $this->request->get('email');
		$password = $this->request->get('password');

		if (empty($email) || empty($password)) {
			return $this->error('');
		}

		if ($this->accountService->login($email, $password)) {
			return $this->success([]);
		} else {
			return $this->error('');
		}
	}

	/**
	 * 用户注册接口
	 * @return Response
	 */
	public function register (): Response
	{
		$email = $this->request->get('email');
		$password = $this->request->get('password');

		if (empty($email) || empty($password)) {
			return $this->error('');
		}

		if ($this->accountService->register($email, $password)) {
			return $this->success([]);
		} else {
			return $this->error('');
		}
	}

	/**
	 * @return Response
	 */
	public function state (): Response
	{
		try {
			$country_id = $this->request->input('country_id');
			$country_list = pluginApp(CountryService::class)->getAll();
			$states = [];
			foreach ($country_list as $c) {
				if ($c['id'] == $country_id) {
					foreach ($c['states'] as $state) {
						$states[] = [
							'id' => $state['id'],
							'name' => $state['name'],
							'country_id' => $state['countryId'],
						];
					}
				}
			}

			return $this->success($states);
		} catch (Throwable $e) {
			return $this->exception($e);
		}
	}

	/**
	 * @return Response
	 */
	public function category (): Response
	{
		return $this->success(pluginApp(CategoryService::class)->getAll());
	}

	/**
	 * @return Response
	 */
	public function itemset (): Response
	{
		return $this->success(pluginApp(ItemSetService::class)->getAll());
	}

	/**
	 * @return Response
	 */
	public function warehouse (): Response
	{
		return $this->success(pluginApp(WarehouseService::class)->getAll());
	}

	/**
	 * @return Response
	 */
	public function stock (): Response
	{
		return $this->success(pluginApp(StockService::class)->listStock());
	}

	/**
	 * @return Response
	 */
	public function item (): Response
	{
		return $this->success(pluginApp(ItemService::class)->getAll());
	}

	/**
	 * @return Response
	 */
	public function blog (): Response
	{
		try {
			return $this->success(pluginApp(BlogService::class)->getAll());
		} catch (Throwable $e) {
			return $this->exception($e);
		}
	}

	public function address ()
	{
		try {
			$address = pluginApp(ContactAddressRepositoryContract::class);
			$account = pluginApp(AccountService::class);
			$contactId = $account->getContactId();
			$addressModel = $address->createAddress([
				//'gender' => 'male',
				'countryId' => 1,//德国
				//'name1' => '',
				'name2' => '金',
				'name3' => '程晨',
				//'vatNumber' => '',
				//'contactPerson' => '',
				'address1' => '浙江省杭州市萧山区江南国际城4-2804',
				'address2' => '浙江省杭州市萧山区江南国际城4-2804',
				'postalCode' => '01013011',
				'town' => '杭州市',
				//'stateId' => '',//浙江的ID
				'companyName' => '公司名称',
				'email' => '326550324@qq.com',
				'phone' => '15868481019'
			], $contactId, 2);

			$invoiceAddress = $address->addAddress($addressModel->id, $contactId, 1);

			return $this->success([
				'version' => 1.0,
				'delivery' => $addressModel,
				'invoice' => $invoiceAddress,
				'list' => $address->getAddresses($contactId)
			]);
		} catch (Throwable $e) {
			return $this->exception($e);
		}
	}

	public function comment ()
	{
		return $this->success([]);
	}

	/**
	 * @return Response
	 */
	public function search (): Response
	{
		try {
			/** @var ItemListService $itemListService */
			$itemListService = pluginApp(ItemListService::class);

			$itemList = $itemListService->getCategoryItem(16, null, 1, 12, true);
			return $this->success($itemList);
		} catch (Exception $e) {
			return $this->success([
				'code' => $e->getCode(),
				'file' => $e->getFile(),
				'message' => $e->getMessage(),
				'trace' => $e->getTrace(),
			]);
		}
	}

	/**
	 * @return Response
	 */
	public function contact (): Response
	{
		return $this->success([
			'getIsAccountLoggedIn' => pluginApp(\Plenty\Modules\Frontend\Services\AccountService::class)->getIsAccountLoggedIn(),
		]);
	}

	/**
	 * @return Response
	 */
	public function country (): Response
	{
		return $this->success(pluginApp(CountryService::class)->getAll());
	}

	/**
	 * test
	 * @return Response
	 */
	public function test (): Response
	{
		try {
			return $this->success([
				'getRequestUri' => $this->request->getRequestUri(),
				'getUri' => $this->request->getUri(),
				'getUserInfo' => $this->request->getUserInfo(),
				'getQueryString' => $this->request->getQueryString(),
				'getUserInfo' => $this->request->getUserInfo(),
			]);
		} catch (Throwable $e) {
			return $this->exception($e);
		}
	}
}
