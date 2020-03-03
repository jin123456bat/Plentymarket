<?php

namespace Plentymarket\Controllers;

use Plenty\Plugin\Http\Request;
use Plenty\Plugin\Http\Response;

/**
 * Class BaseApiController
 * @package Plentymarket\Controllers
 */
class BaseApiController extends BaseController
{
	/**
	 * BaseApiController constructor.
	 * @param Request $request
	 * @param Response $response
	 */
	public function __construct (Request $request, Response $response)
	{
		parent::__construct($request, $response);
	}

	/**
	 * 返回成功信息
	 * @param $data
	 * @return Response
	 */
	protected function success ($data): Response
	{
		return $this->response->make(json_encode([
			'code' => 1,
			'message' => 'OK',
			'data' => $data
		], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), 200, [
			'Content-Type: application/json',
		]);
	}

	/**
	 * 返回失败信息
	 * @param string $message
	 * @return Response
	 */
	protected function error (string $message): Response
	{
		return $this->response->make(json_encode([
			'code' => 0,
			'message' => $message,
		], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), 200, [
			'Content-Type: application/json',
		]);
	}
}
