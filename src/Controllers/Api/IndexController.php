<?php

namespace Plentymarket\Controllers\Api;

use IO\Services\WebstoreConfigurationService;
use Plenty\Plugin\Http\Request;
use Plenty\Plugin\Http\Response;
use Plentymarket\Controllers\BaseApiController;
use Plentymarket\Services\AccountService;
use Plentymarket\Services\CountryService;
use Plentymarket\Services\ItemListService;
use Plentymarket\Services\SessionService;
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
		try {
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
		} catch (\Throwable $e) {
			return $this->exception($e);
		}
	}

	/**
	 * 获取商品信息
	 * @param $product_id
	 * @return Response
	 */
	public function product ($product_id): Response
	{
		try {
			/** @var ItemListService $itemListService */
			$itemListService = pluginApp(ItemListService::class);
			$item = $itemListService->getItem($product_id);
			return $this->success($item);
		} catch (\Exception $e) {
			return $this->exception($e);
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
	 * 设置语言
	 * @return Response
	 */
	public function language (): Response
	{
		try {
			/** @var SessionService $sessionService */
			$sessionService = pluginApp(SessionService::class);
			$id = $this->request->input('id');
			$sessionService->setLang($id);
			return $this->success([
				'id' => $id
			]);
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
//			$commonService = pluginApp(CommonService::class);

			/** @var CountryService $countryService */
			$countryService = pluginApp(CountryService::class);

//			$countrys = $countryService->getCountriesList(0, []);
//			foreach ($countrys as $country) {
//				$countryService->activateCountry($country['id']);
//			}

			$countries = $countryService->getTree();
			foreach ($countries as $country) {
				if (empty($country['states'])) {
					$countryService->deactivateCountry($country['id']);
				}
			}

			return $this->success([
				'all_0' => $countryService->getCountriesList(0, []),
				'all_1' => $countryService->getCountriesList(1, []),
			]);
		} catch (\Throwable $e) {
			return $this->exception($e);
		}
	}
}
