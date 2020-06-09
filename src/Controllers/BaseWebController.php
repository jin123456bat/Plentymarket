<?php

namespace Plentymarket\Controllers;

use Plenty\Plugin\Http\Request;
use Plenty\Plugin\Http\Response;
use Plenty\Plugin\Templates\Twig;
use Plentymarket\Helper\Utils;
use Plentymarket\Services\CommonService;

/**
 * Class BaseWebController
 * @package Plentymarket\Controllers
 */
class BaseWebController extends BaseController
{
	/**
	 * @var Twig
	 */
	protected $twig;

	/**
	 * BaseWebController constructor.
	 * @param Request $request
	 * @param Response $response
	 */
	function __construct (Request $request, Response $response)
	{
		$this->twig = pluginApp(Twig::class);
		parent::__construct($request, $response);
	}

	/**
	 * @param string $template
	 * @param array $breadcrumb
	 * @param array $context
	 * @return string
	 */
	function render (string $template, array $breadcrumb = [], array $context = []): string
	{
		//面包屑
		$context['breadcrumb'] = array_merge([
			$this->trans('Common.home') => '/',
		], $breadcrumb);

		/** @var CommonService $commonService */
		$commonService = pluginApp(CommonService::class);

		//用户信息
		$context['contact'] = $commonService->contract();

		//分类
		$context['category'] = $commonService->category();

		$context = array_merge($context, $commonService->footer_article(1));
		$context = array_merge($context, $commonService->footer_article(2));
		$context = array_merge($context, $commonService->footer_article(3));
		$context = array_merge($context, $commonService->footer_article_list());

		$context['language'] = Utils::getLang();

		return $this->twig->render('Plentymarket::' . $template, $context);
	}

	/**
	 * 生成分页数据
	 * @param int $pages
	 * @param int $current
	 * @param string $class
	 * @return string
	 */
	protected function paginate (int $pages, int $current = 1, $class = ''): string
	{
		$str = '<div class="pagination-content text-center ' . $class . '">
                        <ul>
                            <li><a href="?page=' . ($current - 1) . '"><i class="fa fa-angle-left"></i> <i class="fa fa-angle-left"></i></a></li>
                            ';

		for ($i = 0; $i < $pages; $i++) {
			$active = $i + 1 == $current ? 'class="active"' : '';
			$str .= '<li><a ' . $active . ' href="?page=' . ($i + 1) . '">' . ($i + 1) . '</a></li>';
		}

		$str .= '<li><a href="?page=' . ($current + 1) . '">  <i class="fa fa-angle-right"></i> <i class="fa fa-angle-right"></i> </a></li>
                        </ul>
                    </div>';

		return $str;
	}

	/**
	 * 输出异常信息
	 * @param \Throwable $e
	 * @return string
	 */
	protected function exception (\Throwable $e): string
	{
		return json_encode([
			'code' => 0,
			'message' => $e->getMessage(),
			'data' => [
				'code' => $e->getCode(),
				'file' => $e->getFile(),
				'line' => $e->getLine(),
				'trace' => $e->getTrace(),
			]
		], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
	}
}
