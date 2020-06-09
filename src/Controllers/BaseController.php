<?php

namespace Plentymarket\Controllers;

use Plenty\Plugin\Controller;
use Plenty\Plugin\Http\Request;
use Plenty\Plugin\Http\Response;
use Plenty\Plugin\Translation\Translator;

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
	 * @var Translator
	 */
	private $translator;

	function __construct (Request $request, Response $response)
	{
		$this->request = $request;
		$this->response = $response;
		$this->translator = pluginApp(Translator::class);
	}

	/**
	 * 翻译
	 * @param $key
	 * @param array $params
	 * @param null $locale
	 * @return string
	 */
	protected function trans ($key, $params = [], $locale = null): string
	{
		return $this->translator->trans('Plentymarket::' . $key, $params, $locale);
	}
}
