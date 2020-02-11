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
		$this->translator = pluginApp( Translator::class );
		$this->request = $request;
		$this->response = $response;
	}

	/**
	 * @param $key
	 * @return mixed
	 */
	protected function trans($key)
	{
		return $this->translator->trans($key);
	}

	/**
	 * @param $data
	 * @return Response
	 */
	protected function success($data):Response
	{
		return $this->response->make(json_encode([
			'code' => 1,
			'message'=>'OK',
			'data' => $data
		],JSON_UNESCAPED_UNICODE),200);
	}

	/**
	 * @param $message
	 * @return Response
	 */
	protected function error($message):Response
	{
		return $this->response->make(json_encode([
			'code' => 0,
			'message'=>$message,
		],JSON_UNESCAPED_UNICODE),200);
	}
}
