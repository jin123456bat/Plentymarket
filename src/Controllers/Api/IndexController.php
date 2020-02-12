<?php

namespace Plentymarket\Controllers\Api;

use Plenty\Plugin\Http\Request;
use Plenty\Plugin\Http\Response;
use Plenty\Plugin\Templates\Twig;
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

	// Get all

	/**
	 * @return Response
	 */
	public function index (): Response
	{
		return $this->response->make('index', 501);
	}

	// Post

	/**
	 * @return Response
	 */
	public function store (): Response
	{
		return $this->response->make('login', 200);
		$email = $this->request->get('email');
		$password = $this->request->get('password');

		if (empty($email) || empty($password)) {
//			return $this->error($this->trans("ApiIndex.loginEmailOrPasswordError"));
		}

		if ($this->accountService->login($email, $password)) {
//			return $this->success($this->trans('ApiIndex.loginSuccess'));
		} else {
//			return $this->error($this->trans('ApiIndex.loginEmailOrPasswordError'));
		}
		//return $this->response->create(null, $this->defaultCode);
	}

	// Get

	/**
	 * @param string $selector
	 * @return Response
	 */
	public function show (string $selector): Response
	{
		return $this->response->make('index', 501);
	}

	// Put/patch

	/**
	 * @param string $selector
	 * @return Response
	 */
	public function update (string $selector): Response
	{
		return $this->response->make('index', 501);
	}

	// Delete

	/**
	 * @param string $selector
	 * @return Response
	 */
	public function destroy (string $selector): Response
	{
		return $this->response->make('index', 501);
	}

	/**
	 * @return Response
	 */
//	public function login (): Response
//	{
//		$email = $this->request->get('email');
//		$password = $this->request->get('password');
//
//		if (empty($email) || empty($password)) {
//			return $this->error($this->trans("ApiIndex.loginEmailOrPasswordError"));
//		}
//
//		if ($this->accountService->login($email, $password)) {
//			return $this->success($this->trans('ApiIndex.loginSuccess'));
//		} else {
//			return $this->error($this->trans('ApiIndex.loginEmailOrPasswordError'));
//		}
//	}
//
//	/**
//	 * @return Response
//	 */
//	public function register (): Response
//	{
//		$email = $this->request->get('email');
//		$password = $this->request->get('password');
//
//		if (empty($email) || empty($password)) {
//			return $this->error($this->trans("ApiIndex.registerEmailOrPasswordError"));
//		}
//
//		if ($this->accountService->register($email, $password)) {
//			return $this->success($this->trans("ApiIndex.registerSuccess"));
//		} else {
//			return $this->error($this->trans("ApiIndex.registerEmailExist"));
//		}
//	}
}
