<?php

namespace Plentymarket\Controllers\Web;

use Plenty\Modules\Blog\Models\BlogPost;
use Plentymarket\Controllers\BaseWebController;
use Plentymarket\Services\BlogService;
use Plentymarket\Services\HomeService;
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
		try {
			/** @var HomeService $homeService */
			$homeService = pluginApp(HomeService::class);
			return $this->render('index.index', [
			], [
				'categoryList' => $homeService->product_category(),
				'product_new' => $homeService->product_new(),
				'product_deals' => $homeService->product_deals(),
				'product_popular' => $homeService->product_popular(),
				'product_top' => $homeService->product_top(),
				'category_blog' => $homeService->article(),
			]);
		} catch (\Throwable $e) {
			return $this->exception($e);
		}
	}

	/**
	 * 关于我们页面
	 * @return string
	 */
	function about (): string
	{
		try {
			return $this->render('index.about', [
				$this->trans('WebIndexAbout.about') => '/index/about'
			]);
		} catch (\Throwable $e) {
			return $this->exception($e);
		}
	}

	/**
	 * 联系我们页面
	 * @return string
	 */
	function contact (): string
	{
		try {
			return $this->render('index.contact', [
				$this->trans('WebIndexContact.contact') => '/index/contact'
			]);
		} catch (\Throwable $e) {
			return $this->exception($e);
		}
	}

	/**
	 * FAQ页面
	 * @return string
	 */
	function faq (): string
	{
		try {
			return $this->render('index.faq', [
				$this->trans('WebIndexFaq.faq') => '/index/faq'
			]);
		} catch (\Throwable $e) {
			return $this->exception($e);
		}
	}

	/**
	 * 登录或者注册页面
	 * @return string
	 */
	function login_register (): string
	{
		try {
			return $this->render('index.login-register', [
				$this->trans('WebIndexLoginRegister.loginRegister') => '/index/login_register'
			]);
		} catch (\Throwable $e) {
			return $this->exception($e);
		}
	}

	/**
	 * 商品搜索页面
	 * @param $query
	 * @return string
	 */
	function search (): string
	{
		try {
			$query = $this->request->get('query', '');
			$page = $this->request->get('page', 1);
			$sort = $this->request->get('sort');

			/** @var ItemListService $itemListService */
			$itemListService = pluginApp(ItemListService::class);
			$itemList = $itemListService->searchItem($query, $sort, $page, 12);

			$paginate = $this->paginate(ceil($itemList['total'] / 12), $page, 'text-md-right');

			return $this->render('index.product-list-category', [
			], [
				'query' => $query,
				'items' => $itemList,
				'page' => $page,
				'paginate' => $paginate,
			]);
		} catch (\Throwable $e) {
			return $this->exception($e);
		}
	}

	/**
	 * 分类商品列表
	 * @return string
	 */
	function product_list_category ($category_id): string
	{
		try {
			$page = $this->request->get('page', 1);
			$sort = $this->request->get('sort');

			/** @var ItemListService $itemListService */
			$itemListService = pluginApp(ItemListService::class);
			$itemList = $itemListService->getCategoryItem($category_id, $sort, $page, 12);

			$paginate = $this->paginate(ceil($itemList['total'] / 12), $page, 'text-md-right');

			return $this->render('index.product-list-category', [
			], [
				'category_id' => $category_id,
				'items' => $itemList,
				'page' => $page,
				'paginate' => $paginate,
			]);
		} catch (\Throwable $e) {
			return $this->exception($e);
		}
	}

	/**
	 * 商品详情页
	 * @param $product_id
	 * @return string
	 */
	function product ($product_id): string
	{
		try {
			/** @var ItemListService $itemListService */
			$itemListService = pluginApp(ItemListService::class);
			$item = $itemListService->getItem($product_id);

			$crossSelling = empty($item['crossSelling']) ? [
				'total' => 0,
				'list' => [],
			] : $itemListService->getItems($item['crossSelling']);

			return $this->render('index.product', [
			], [
				'crossSelling' => $crossSelling,
				'item' => $item
			]);
		} catch (\Exception $e) {
			return $this->exception($e);
		}
	}

	/**
	 * 文章列表
	 * @return string
	 */
	function blog_list (): string
	{
		try {
			$page = $this->request->get('page', 1);
			$list = pluginApp(BlogService::class)->getAll($page, 9);

			foreach ($list['entries'] as $key => $r) {
				$list['entries'][$key]['createdAt'] = date('d F, Y', strtotime($list['entries'][$key]['createdAt']));

				$list['entries'][$key]['data']['images'] = array_filter(array_map(function ($value) {
					return $value['path'];
				}, $r['data']['images']));
			}

			$paginate = $this->paginate(ceil($list['totalsCount'] / 9), $page);

			return $this->render('index.blog-list', [
				$this->trans('WebIndexBlogList.blog') => '/index/blog_list'
			], [
				'list' => $list,
				'paginate' => $paginate,
			]);
		} catch (\Throwable $e) {
			return $this->exception($e);
		}
	}

	private function htmlspecialchars_decode ($string, $style = ENT_COMPAT)
	{
		$data = [
			'"' => '&quot;',
			'\'' => '&#39;',
			'<' => '&lt;',
			'>' => '&gt;',
			'&' => '&amp;',
		];
		return str_replace(array_keys($data), array_values($data), $string);
	}

	/**
	 * 文章详情
	 * @param $blog_id
	 * @return string
	 */
	function blog ($blog_id): string
	{
		try {
			/** @var BlogPost $blog */
			$blog = pluginApp(BlogService::class)->get($blog_id);
			$blog = $blog->toArray();

			$blog['createdAt'] = date('d F, Y', strtotime($blog['createdAt']));

			$blog['data']['images'] = array_filter(array_map(function ($value) {
				return $value['path'];
			}, $blog['data']['images']));

			return $this->render('index.blog', [
				$this->trans('WebIndexBlog.blog') => '/index/blog',
			], [
				'blog' => $blog
			]);
		} catch (\Throwable $e) {
			return $this->exception($e);
		}
	}
}
