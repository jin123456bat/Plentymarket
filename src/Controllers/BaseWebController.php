<?php
namespace Plentymarket\Controllers;

use Plenty\Plugin\Http\Request;
use Plenty\Plugin\Http\Response;
use Plenty\Plugin\Templates\Twig;
use Plentymarket\Services\CategoryService;

/**
 * Class BaseWebController
 * @package Plentymarket\Controllers
 */
class BaseWebController extends BaseController
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
		parent::__construct();
	}

	/**
	 * @param string $template
	 * @param array $context
	 * @return string
	 */
	function render (string $template, array $breadcrumb = [], array $context = []): string
	{
		//面包屑
		$context['breadcrumb'] = array_merge([
			$this->trans('Common.home') => '/',
		], $breadcrumb);

		//分类
		$context['category'] = pluginApp(CategoryService::class)->getTree();

		return $this->twig->render('Plentymarket::' . $template, $context);
	}
}
