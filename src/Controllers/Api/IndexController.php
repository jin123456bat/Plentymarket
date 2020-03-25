<?php

namespace Plentymarket\Controllers\Api;

use Exception;
use Plenty\Modules\Frontend\PaymentMethod\Contracts\FrontendPaymentMethodRepositoryContract;
use Plenty\Plugin\Http\Request;
use Plenty\Plugin\Http\Response;
use Plentymarket\Controllers\BaseApiController;
use Plentymarket\Helper\Utils;
use Plentymarket\Services\AccountService;
use Plentymarket\Services\BlogService;
use Plentymarket\Services\ConfigService;
use Plentymarket\Services\CountryService;
use Plentymarket\Services\ItemListService;
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

		try {
			$this->accountService->login($email, $password);
			return $this->success([]);
		} catch (\Throwable $e) {
			return $this->exception($e);
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
	 * 根据国家ID获取城市信息
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
	public function blog (): Response
	{
		try {
			return $this->success(pluginApp(BlogService::class)->getAll());
		} catch (Throwable $e) {
			return $this->exception($e);
		}
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
			return $this->exception($e);
		}
	}

	/**
	 * @return Response
	 */
	public function country (): Response
	{
		try {
			$country = pluginApp(CountryService::class)->getAll();
			return $this->success($country);
		} catch (Throwable $e) {
			return $this->exception($e);
		}
	}

	/**
	 * 支付方式
	 * @return Response
	 */
	public function payment (): Response
	{
		try {
			$methodOfPaymentList = pluginApp(FrontendPaymentMethodRepositoryContract::class)->getCurrentPaymentMethodsList();
			return $this->success($methodOfPaymentList);
		} catch (\Throwable $e) {
			return $this->exception($e);
		}
	}

	/**
	 * test
	 * @return Response
	 */
	public function test (): Response
	{
		try {
			return $this->success([
				'lang' => Utils::getLang(),
				'getRequestUri' => $this->request->getRequestUri(),
				'getUri' => $this->request->getUri(),
				'getUserInfo' => $this->request->getUserInfo(),
				'getQueryString' => $this->request->getQueryString(),
				'getUserInfo' => $this->request->getUserInfo(),
				'getActiveLanguageList' => pluginApp(ConfigService::class)->getActiveLanguageList()
			]);
		} catch (Throwable $e) {
			return $this->exception($e);
		}
	}
}
