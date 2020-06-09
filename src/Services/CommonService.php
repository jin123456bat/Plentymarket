<?php

namespace Plentymarket\Services;

/**
 * Class CommonService
 * @package Plentymarket\Services
 */
class CommonService
{
	/**
	 * @var AccountService
	 */
	private $accountService;

	private $configService;

	private $categoryService;

	private $blogService;

	/**
	 * CommonService constructor.
	 * @param AccountService $accountService
	 */
	function __construct (AccountService $accountService, ConfigService $configService, CategoryService $categoryService, BlogService $blogService)
	{
		$this->accountService = $accountService;
		$this->configService = $configService;
		$this->categoryService = $categoryService;
		$this->blogService = $blogService;
	}

	/**
	 *
	 */
	function contract ()
	{
		return $this->accountService->getContact();
	}

	function category ()
	{
		$this->categoryService->getTree();
	}

	function footer_article ($index)
	{
		$footer_article = $this->configService->getTemplateConfig('basic.footer_article_' . $index);
		if (!empty($footer_article)) {
			return [
				'footer_article_' . $index => $this->categoryService->get($footer_article),
				'footer_article_' . $index . '_list' => $this->blogService->category_id($footer_article)
			];
		}
	}

	function footer_article_list ()
	{
		$footer_article_4 = $this->configService->getTemplateConfig('basic.footer_article_4');
		if (!empty($footer_article_4)) {
			return ['footer_article_4_list' => $this->blogService->category_id($footer_article_4)];
		}
	}
}
