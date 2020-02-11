<?php

namespace Plentymarket\Controllers\Api;

use Plenty\Plugin\Http\Request;
use Plenty\Plugin\Http\Response;
use Plenty\Plugin\Translation\Translator;
use Plentymarket\Controllers\BaseApiController;
use Plentymarket\Services\AccountService;

/**
 * Class ContentController
 * @package HelloWorld\Controllers
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
	 *
	 */
	public function login ()
	{
		return $this->success('登录成功');
	}

	/**
	 * @return Response
	 */
	public function register ()
	{
		$email = $this->request->get('email');
		$password = $this->request->get('password');

		if (empty($email) || empty($password))
		{
			return $this->error($this->trans("Plentymarket::Register.emailOrPasswordError"));
		}

		if ($this->accountService->register($email, $password)) {
			return $this->success($this->trans("Plentymarket::Register.success"));
		} else {
			return $this->error($this->trans("Plentymarket::Register.emailExist"));
		}
	}
}
