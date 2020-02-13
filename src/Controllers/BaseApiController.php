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
	 * @var Translator
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
	 * @return mixed
	 */
	protected function trans ($key)
	{
		return $this->translator->trans('Plentymarket::' . $key);
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
		], JSON_UNESCAPED_UNICODE), 200, [
			'Content-Type: application/json',
		]);
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
		], JSON_UNESCAPED_UNICODE), 200, [
			'Content-Type: application/json',
		]);
	}
}
