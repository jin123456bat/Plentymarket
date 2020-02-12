<?php
namespace Plentymarket\Controllers;

use Plenty\Plugin\Controller;
use Plenty\Plugin\Http\Request;
use Plenty\Plugin\Http\Response;
use Plenty\Plugin\Translation\Translator;

/**
 * Class BaseApiController
 * @package Plentymarket\Controllers
 */
class BaseApiController extends Controller
{
	/**
	 * @var Response
	 */
	protected $response;

	/**
	 * @var Request
	 */
	protected $request;

	/**
	 * @var |null
	 */
	private $translator;

	/**
	 * BaseApiController constructor.
	 * @param Request $request
	 * @param Response $response
	 */
	public function __construct (Request $request, Response $response)
	{
		$this->request = $request;
		$this->response = $response;
		$this->translator = pluginApp(Translator::class);
	}

	/**
	 * 翻译
	 * @param $key
	 * @return string
	 */
	protected function trans ($key): string
	{
		return $this->translator->trans('Plentymarket::' . $key);
	}

//	/**
//	 * 返回成功信息
//	 * @param $data
//	 * @return string
//	 */
//	protected function success ($data): string
//	{
//		return json_encode([
//			'code' => 1,
//			'message' => 'OK',
//			'data' => $data
//		]);
//	}
//
//	/**
//	 * 返回失败信息
//	 * @param $message
//	 * @return string
//	 */
//	protected function error ($message): string
//	{
//		return json_encode([
//			'code' => 0,
//			'message' => $message,
//		]);
//	}

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
		], JSON_UNESCAPED_UNICODE), 200);
	}

	/**
	 * 返回失败信息
	 * @param $message
	 * @return Response
	 */
	protected function error ($message): Response
	{
		return $this->response->make(json_encode([
			'code' => 0,
			'message' => $message,
		], JSON_UNESCAPED_UNICODE), 200);
	}
}
