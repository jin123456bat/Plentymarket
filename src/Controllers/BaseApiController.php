<?php
namespace Plentymarket\Controllers;

use Plenty\Plugin\Controller;
use Plenty\Plugin\Http\Request;
use Plenty\Plugin\Http\Response;

class BaseApiController extends Controller
{
	protected $response;

	protected $request;

	public function __construct (Request $request,Response $response)
	{
		$this->request = $request;
		$this->response = $response;
	}

	protected function success($data):Response
	{
		return $this->response->make(json_encode([
			'code' => 1,
			'message'=>'OK',
			'data' => $data
		],JSON_UNESCAPED_UNICODE),200);
	}

	protected function error($message):Response
	{
		return $this->response->make(json_encode([
			'code' => 0,
			'message'=>$message,
		],JSON_UNESCAPED_UNICODE),200);
	}
}
