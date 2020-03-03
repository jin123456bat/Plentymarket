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
	 * @return string
	 */
	protected function trans ($key): string
	{
		return $this->translator->trans('Plentymarket::' . $key);
	}

	protected function exception (\Exception $e): Response
	{
		return $this->response->make(json_encode([
			'code' => 0,
			'message' => $e->getMessage(),
			'data' => [
				'code' => $e->getCode(),
				'file' => $e->getFile(),
				'line' => $e->getLine(),
				'trace' => $e->getTrace(),
			]
		], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), 200, [
			'Content-Type' => 'application/json',
		]);
	}
}
