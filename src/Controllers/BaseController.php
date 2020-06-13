<?php

namespace Plentymarket\Controllers;

use Plenty\Plugin\Controller;
use Plenty\Plugin\Http\Request;
use Plenty\Plugin\Http\Response;
use Plentymarket\Services\TranslateService;

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

	/**
	 * @var TranslateService
	 */
	private $translator;

	function __construct (Request $request, Response $response)
	{
		$this->request = $request;
		$this->response = $response;
		$this->translator = pluginApp(TranslateService::class);
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
}
