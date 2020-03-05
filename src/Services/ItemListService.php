<?php

namespace Plentymarket\Services;

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
			'currency' => $data['data']['prices']['default']['currency'],//货币单位
			'main_price' => $data['data']['prices']['default']['price']['value'],//价格
			'format_main_price' => $data['data']['prices']['default']['price']['formatted'],//格式化价格

			'discount_price' => $data['data']['prices']['default']['price']['value'],//价格
			'format_discount_price' => $data['data']['prices']['default']['price']['formatted'],//格式化价格

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
