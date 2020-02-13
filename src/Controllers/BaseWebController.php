<?php
namespace Plentymarket\Controllers;

use Plenty\Plugin\Controller;
use Plenty\Plugin\Http\Request;
use Plenty\Plugin\Http\Response;
use Plenty\Plugin\Templates\Twig;

/**
 * Class BaseWebController
 * @package Plentymarket\Controllers
 */
class BaseWebController extends Controller
{
	/**
	 * @var |null
	 */
	protected $twig;
	/**
	 * @var Request
	 */
	protected $request;
	/**
	 * @var Response
	 */
	protected $response;

	/**
	 * BaseWebController constructor.
	 * @param Request $request
	 * @param Response $response
	 */
	function __construct (Request $request, Response $response)
	{
		$this->request = $request;
		$this->response = $response;
		$this->twig = pluginApp(Twig::class);
	}

	/**
	 * @param string $template
	 * @param array $context
	 * @return string
	 */
	function render (string $template, array $context = []): string
	{
		$context['breadcrumb'] = [
			'Home' => '/',
			'Login Register' => '/index/login_register'
		];
		return $this->twig->render('Plentymarket::' . $template, $context);
	}
}
