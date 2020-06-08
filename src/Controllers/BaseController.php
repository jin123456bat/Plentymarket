<?php

namespace Plentymarket\Controllers;

use Plenty\Plugin\Controller;
use Plenty\Plugin\Http\Request;
use Plenty\Plugin\Http\Response;

class BaseController extends Controller
{
	/**
	 * @var Response
	 */
	protected $response;

	/**
	 * @var Request
	 */
	protected $request;

	function __construct (Request $request, Response $response)
	{
		$this->request = $request;
		$this->response = $response;
	}

	/**
	 * 翻译
	 * @param $key
	 * @return string
	 */
	protected function trans ($key): string
	{
		return app('translator')->trans('Plentymarket::' . $key, [], 'messages', null);
//		return trans('Plentymarket::' . $key);
	}
}
