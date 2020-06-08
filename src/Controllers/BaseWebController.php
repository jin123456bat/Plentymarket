<?php

namespace Plentymarket\Controllers;

use Plenty\Plugin\Http\Request;
use Plenty\Plugin\Http\Response;
use Plenty\Plugin\Templates\Twig;
use Plentymarket\Helper\Utils;
use Plentymarket\Services\AccountService;
use Plentymarket\Services\BlogService;
use Plentymarket\Services\CategoryService;
use Plentymarket\Services\ConfigService;

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

		/** @var AccountService $accountService */
		$accountService = pluginApp(AccountService::class);

		/** @var CategoryService $categoryService */
		$categoryService = pluginApp(CategoryService::class);

		/** @var BlogService $blogService */
		$blogService = pluginApp(BlogService::class);

		/** @var ConfigService $configService */
		$configService = pluginApp(ConfigService::class);

		//用户信息
		$context['contact'] = $accountService->getContact();

		//分类
		$context['category'] = $categoryService->getTree();

		//footer中的文章信息
		$footer_article_1 = $configService->getTemplateConfig('basic.footer_article_1');
		if (!empty($footer_article_1)) {
			$context['footer_article_1'] = $categoryService->get($footer_article_1);
			$context['footer_article_1_list'] = $blogService->category_id($footer_article_1);
		}

		$footer_article_2 = $configService->getTemplateConfig('basic.footer_article_2');
		if (!empty($footer_article_2)) {
			$context['footer_article_2'] = $categoryService->get($footer_article_2);
			$context['footer_article_2_list'] = $blogService->category_id($footer_article_2);
		}

		$footer_article_3 = pluginApp(ConfigService::class)->getTemplateConfig('basic.footer_article_3');
		if (!empty($footer_article_3)) {
			$context['footer_article_3'] = $categoryService->get($footer_article_3);
			$context['footer_article_3_list'] = $blogService->category_id($footer_article_3);
		}

		$footer_article_4 = $configService->getTemplateConfig('basic.footer_article_4');
		if (!empty($footer_article_4)) {
			$context['footer_article_4_list'] = $blogService->category_id($footer_article_4);
		}

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
