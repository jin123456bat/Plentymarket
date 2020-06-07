<?php

namespace Plentymarket\Controllers\Web;

use Plenty\Modules\Blog\Models\BlogPost;
use Plentymarket\Controllers\BaseWebController;
use Plentymarket\Services\BlogService;
use Plentymarket\Services\CategoryService;
use Plentymarket\Services\ConfigService;
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
		/** @var ConfigService $configService */
		$configService = pluginApp(ConfigService::class);
		/** @var CategoryService $categoryService */
		$categoryService = pluginApp(CategoryService::class);
		/** @var ItemListService $itemListService */
		$itemListService = pluginApp(ItemListService::class);
		/** @var BlogService $blogService */
		$blogService = pluginApp(BlogService::class);

		$categoryList = [];
		for ($i = 1; $i <= 4; $i++) {
			$category = $categoryService->get($configService->getTemplateConfig('basic.home_category_' . $i))->toArray();
			$category['details'][0]['imagePath'] = 'https://' . $this->request->getHttpHost() . '/documents/' . $category['details'][0]['imagePath'];
			$categoryList['home_category_' . $i] = $category;
		}

		//特惠
//		$home_product_new = $configService->getTemplateConfig('basic.home_product_deals');
		$home_product_deals_string = '139:2020-07-08,147:2020-07-08,153:2020-07-08';
		$home_product_deals = explode(',', $home_product_new_string);
		$data = [];
		foreach ($home_product_deals as $value) {
			list($itemId, $endtime) = explode(':', $value, 2);
			$data[$itemId] = $endtime;
		}
		$home_product_deals = pluginApp(ItemListService::class)->getItems(array_keys($data));
		$home_product_deals_list = $home_product_deals['list'];
		foreach ($home_product_deals_list as &$item) {
			$item['countdown'] = date('Y/m/d', strtotime($data[$item['id']] ?? date('Y/m/d')));
		}
		$home_product_deals['list'] = $home_product_deals_list;

		//流行
//		$home_product_popular_string = $configService->getTemplateConfig('basic.home_product_popular');
		$home_product_popular_string = '139,147,153,155-184';
		$home_product_popular = explode(',', $home_product_popular_string);
		$data = [];
		foreach ($home_product_popular as $value) {
			if (strpos($value, '-')) {
				list($start, $end) = explode('-', $value, 2);
				$data = array_merge($data, range($start, $end));
			} else {
				$data[] = (int)$value;
			}
		}
		$home_product_popular = $itemListService->getItems(array_filter(array_unique($data)));

		//TOP
//		$home_product_top_string = $configService->getTemplateConfig('basic.home_product_top');
		$home_product_top_string = '139,147,153,155-184';
		$home_product_top = explode(',', $home_product_top_string);
		$data = [];
		foreach ($home_product_top as $value) {
			if (strpos($value, '-')) {
				list($start, $end) = explode('-', $value, 2);
				$data = array_merge($data, range($start, $end));
			} else {
				$data[] = (int)$value;
			}
		}
		$home_product_top = $itemListService->getItems(array_filter(array_unique($data)));

		//最底部文章
		$home_category_blog = $configService->getTemplateConfig('basic.home_category_blog');
		if (!empty($home_category_blog)) {
			$home_category_blog_list = $blogService->category_id($home_category_blog);
			foreach ($home_category_blog_list as $key => $r) {
				$home_category_blog_list[$key]['data']['images'] = array_filter(array_map(function ($value) {
					return $value['path'];
				}, $r['data']['images']));
			}
		}

		//新品
//		$home_product_new = $configService->getTemplateConfig('basic.home_product_new');
		$home_product_new_string = '139,147,153,155-184';
		$home_product_new_array = explode(',', $home_product_new_string);
		$data = [];
		foreach ($home_product_new_array as $value) {
			if (strpos($value, '-')) {
				list($start, $end) = explode('-', $value, 2);
				$data = array_merge($data, range($start, $end));
			} else {
				$data[] = (int)$value;
			}
		}
		$home_product_new = $itemListService->getItems(array_filter(array_unique($data)));

		return $this->render('index.index', [

		], [
			'categoryList' => $categoryList,
			'product_new' => $home_product_new,
			'product_deals' => $home_product_deals,
			'product_popular' => $home_product_popular,
			'product_top' => $home_product_top,
			'category_blog' => $home_category_blog_list ?? []
		]);
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
	 * 商品搜索页面
	 * @param $query
	 * @return string
	 */
	function search (): string
	{
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

		$paginate = $this->paginate(ceil($itemList['total'] / 12), $page, 'text-md-right');

		return $this->render('index.product-list-category', [
		], [
			'category_id' => $category_id,
			'items' => $itemList,
			'page' => $page,
			'paginate' => $paginate,
		]);
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

			return $this->render('index.product', [
			], [
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
	}
}
