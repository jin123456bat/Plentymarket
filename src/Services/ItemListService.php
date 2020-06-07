<?php

namespace Plentymarket\Services;

use Plentymarket\Extensions\Filters\NumberFormatFilter;
use Plentymarket\Helper\Utils;
use Plentymarket\Models\Wishlist;
use Plentymarket\Services\ItemSearch\SearchPresets\CategoryItems;
use Plentymarket\Services\ItemSearch\Services\ItemSearchService;

/**
 * Class ItemListService
 * @package Plentymarket\Services
 */
class ItemListService
{
	/**
	 * @return array
	 */
	public function getItemsFromWishlist ()
	{
		$wishlist = pluginApp(Wishlist::class);
		$itemIds = $wishlist->getContactItemId();
		if (empty($itemIds)) {
			return [];
		}

		$list = $this->getItemVariationIds($itemIds);

		$numberFormatFilter = pluginApp(NumberFormatFilter::class);

		$list = array_map(function ($item) use ($numberFormatFilter) {
			$item['image'] = empty($item['images']) ? '' : current($item['images']);
			$item['vat_price'] = $numberFormatFilter->formatMonetary($item['vat'] * $item['discount_price'] / 100, Utils::getCurrency());
			return $item;
		}, $list);

		return $list;
	}

	/**
	 * 获取购物车中的商品列表
	 * @return array
	 */
	public function getItemsFromBasket ()
	{
		$list = pluginApp(BasketService::class)->getAll();
		$variationId = [];
		$dict = [];
		foreach ($list as $value) {
			$variationId[] = $value['variationId'];
			$dict[$value['variationId']] = [
				'quantity' => $value['quantity'],
				'basketItemId' => $value['id'],
			];
		}

		if (empty($variationId)) {
			return [];
		}

		$data = $this->getItemVariationIds($variationId);

		$numberFormatFilter = pluginApp(NumberFormatFilter::class);

		return array_map(function ($item) use ($dict, $numberFormatFilter) {
			$item['image'] = empty($item['images']) ? '' : current($item['images']);
			$item['quantity'] = $dict[$item['variationId']]['quantity'];
			$item['basketItemId'] = $dict[$item['variationId']]['basketItemId'];
			$item['vat_price'] = $numberFormatFilter->formatMonetary($item['vat'] * $item['quantity'] * $item['discount_price'] / 100, Utils::getCurrency());
			$item['format_discount_price_quantity'] = $numberFormatFilter->formatMonetary((1 + $item['vat'] / 100) * $item['quantity'] * $item['discount_price'], Utils::getCurrency());
			return $item;
		}, $data['list']);
	}

	/**
	 * 商品搜索
	 * @param $query
	 * @param null $sorting
	 * @param int $page
	 * @param int $maxItems
	 * @param bool $source
	 * @return array
	 */
	public function searchItem ($query, $sorting = null, $page = 1, $maxItems = 12, $source = false): array
	{
		/** @var ItemSearchService $searchService */
		$searchService = pluginApp(ItemSearchService::class);

		$searchFactory = CategoryItems::getSearchFactory([
			'query' => $query,
			'sorting' => $sorting
		]);

		$searchFactory->setPage($page, $maxItems);

		if ($source) {
			return $searchService->getResult($searchFactory);
		}
		return $this->formatItemsList($searchService->getResult($searchFactory));
	}

	/**
	 * @param null $id
	 * @param null $sorting
	 * @param int $page
	 * @param int $maxItems
	 * @param bool $source
	 * @return array
	 */
	public function getCategoryItem ($id = null, $sorting = null, $page = 1, $maxItems = 12, $source = false)
	{
		/** @var ItemSearchService $searchService */
		$searchService = pluginApp(ItemSearchService::class);

		$searchFactory = CategoryItems::getSearchFactory([
			'categoryId' => $id,
			'sorting' => $sorting
		]);

		$searchFactory->setPage($page, $maxItems);

		if ($source) {
			return $searchService->getResult($searchFactory);
		}
		return $this->formatItemsList($searchService->getResult($searchFactory));
	}

	/**
	 * @param $variationIds
	 * @param int $page
	 * @param int $maxItems
	 * @param bool $source
	 * @return array
	 */
	public function getItemVariationIds ($variationIds, $page = 1, $maxItems = 12, $source = false)
	{
		/** @var ItemSearchService $searchService */
		$searchService = pluginApp(ItemSearchService::class);

		$searchFactory = CategoryItems::getSearchFactory([
			'variationIds' => $variationIds,
		]);

		$searchFactory->setPage($page, $maxItems);

		if ($source) {
			return $searchService->getResult($searchFactory);
		}
		return $this->formatItemsList($searchService->getResult($searchFactory));
	}

	/**
	 * @param $itemId
	 * @param bool $source
	 * @return array|mixed
	 */
	public function getItem ($itemId, $source = false)
	{
		$searchService = pluginApp(ItemSearchService::class);

		$searchFactory = CategoryItems::getSearchFactory([
			'itemId' => $itemId
		]);

		$searchFactory->setPage(1, 1);
		$result = $searchService->getResult($searchFactory);

		if (empty($result['documents'])) {
			return [];
		}

		if ($source) {
			return current($result['documents']);
		}

		return $this->formatItem(current($result['documents']));
	}

	/**
	 * @param array $itemIds
	 * @param bool $source
	 * @return array
	 */
	public function getItems (array $itemIds, $source = false)
	{
		/** @var ItemSearchService $searchService */
		$searchService = pluginApp(ItemSearchService::class);

		$searchFactory = CategoryItems::getSearchFactory([
			'itemIds' => $itemIds,
		]);

		$searchFactory->setPage(1, count($itemIds));

		if ($source) {
			return $searchService->getResult($searchFactory);
		}
		return $this->formatItemsList($searchService->getResult($searchFactory));
	}

	/**
	 * @param $data
	 * @param string $key
	 * @return mixed
	 */
	private function getSalesPrices ($data, string $key)
	{
		foreach ($data as $r) {
			if ($r['type'] == 'default') {
				return $r[$key];
			}
		}
	}

	/**
	 * @param $data
	 * @return array
	 */
	private function formatItem ($data)
	{
		static $wishlist;
		if (empty($wishlist)) {
			$wishlist = pluginApp(Wishlist::class);
		}

		return [
			'id' => $data['data']['item']['id'],//商品ID
			'variationId' => $data['id'],//添加购物车用这个ID
			'name' => $data['data']['texts']['name1'],//商品名称
			'images' => array_column($data['data']['images']['all'], 'url'),//商品图片
			'category_id' => array_column($data['data']['defaultCategories'], 'id'),//所处分类
			'country' => $data['data']['item']['producingCountry']['names']['name'],//原产国
			'manufacturer' => $data['data']['item']['manufacturer']['name'],//供应商
			'min_num' => $this->getSalesPrices($data['data']['salesPrices'], 'minimumOrderQuantity'),//最低购买量
			'main_price' => $data['data']['prices']['rrp']['price']['value'],//价格
			'format_main_price' => $data['data']['prices']['default']['price']['formatted'],//格式化价格
			'discount_price' => $data['data']['prices']['default']['price']['value'],//价格
			'format_discount_price' => $data['data']['prices']['default']['price']['formatted'],//格式化价格
			'discount' => ceil(100 * ($data['data']['prices']['rrp']['price']['value'] - $data['data']['prices']['default']['price']['value']) / $data['data']['prices']['rrp']['price']['value']),
			'currency' => $data['data']['prices']['default']['currency'],//货币
			'stock' => $data['data']['stock']['net'],//库存
			'desc' => strip_tags($data['data']['texts']['description']),//商品描述
			'short_desc' => strip_tags($data['data']['texts']['shortDescription']),//短描述
			'unit' => $data['data']['unit']['names']['name'],//单位
			'vat' => $data['data']['prices']['default']['vat']['value'],//增值税
			'wishlist' => $wishlist->has($data['id']),//是否添加到愿望清单了
		];
	}

	/**
	 * @param $data
	 * @return array
	 */
	private function formatItemsList ($data)
	{
		$list = [];
		foreach ($data['documents'] as $item) {
			$list[] = $this->formatItem($item);
		}

		return [
			'total' => $data['total'],//总数
			'list' => $list,//列表
		];
	}
}
