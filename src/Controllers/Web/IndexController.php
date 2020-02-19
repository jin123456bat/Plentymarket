<?php

namespace Plentymarket\Controllers\Web;

use Plentymarket\Controllers\BaseWebController;
use Plentymarket\Services\ItemListService;

/**
 * Class ContentController
 * @package Plentymarket\Controllers\Web
 */
class IndexController extends BaseWebController
{
	/**
	 * 首页
	 * @return string
	 */
	public function index (): string
	{
		return $this->render('index.index');
	}

	/**
	 * 关于我们页面
	 * @return string
	 */
	function about (): string
	{
		return $this->render('index.about', [
			$this->trans('WebIndexAbout.about') => '/index/about'
		]);
	}

	/**
	 * 联系我们页面
	 * @return string
	 */
	function contact (): string
	{
		return $this->render('index.contact', [
			$this->trans('WebIndexContact.contact') => '/index/contact'
		]);
	}

	/**
	 * FAQ页面
	 * @return string
	 */
	function faq (): string
	{
		return $this->render('index.faq', [
			$this->trans('WebIndexFaq.faq') => '/index/faq'
		]);
	}

	/**
	 * 登录或者注册页面
	 * @return string
	 */
	function login_register (): string
	{
		return $this->render('index.login-register', [
			$this->trans('WebIndexLoginRegister.loginRegister') => '/index/login_register'
		]);
	}

	/**
	 * 分类商品列表
	 * @return string
	 */
	function product_list_category ($category_id): string
	{
		$page = $this->request->get('page', 1);
		$sort = $this->request->get('sort');

		/** @var ItemListService $itemListService */
		$itemListService = pluginApp(ItemListService::class);
		$itemList = $itemListService->getCategoryItem($category_id, $sort, $page, 12);

		return $this->render('index.product-list-category', [
		], [
			'category_id' => $category_id,
			'items' => $itemList,
			'page' => $page
		]);
	}

	/**
	 * 商品详情页
	 * @param $product_id
	 * @return string
	 */
	function product ($product_id): string
	{
		return $this->render('index.product');
	}

	/**
	 * 文章列表
	 * @return string
	 */
	function blog_list (): string
	{
		return $this->render('index.blog-list', [
			$this->trans('WebIndexBlogList.blog') => '/index/blog_list'
		]);
	}

	/**
	 * 文章详情
	 * @param $blog_id
	 * @return string
	 */
	function blog ($blog_id): string
	{
		return $this->render('index.product-list-category', [
			$this->trans('WebIndexBlog.blog') => '/index/blog',
		], [
			'blog_id' => $blog_id
		]);
	}
}
