<?php

namespace Plentymarket\Services;

use Plentymarket\Services\ItemSearch\SearchPresets\CategoryItems;
use Plentymarket\Services\ItemSearch\Services\ItemSearchService;

class ItemListService
{
	public function getCategoryItem ($id = null, $sorting = null, $page = 1, $maxItems = 12)
	{
		/** @var ItemSearchService $searchService */
		$searchService = pluginApp(ItemSearchService::class);

		$searchFactory = CategoryItems::getSearchFactory([
			'categoryId' => $id,
			'sorting' => $sorting
		]);

		$searchFactory->setPage($page, $maxItems);

		return $this->formatItemsList($searchService->getResult($searchFactory));
	}

	public function getItem ($itemId)
	{
		$searchService = pluginApp(ItemSearchService::class);

		$searchFactory = CategoryItems::getSearchFactory([
			'itemId' => $itemId
		]);

		$searchFactory->setPage(1, 1);
		$result = $searchService->getResult($searchFactory);
		return $this->formatItem(current($result['documents']));
	}

	private function getSalesPrices (array $data, string $key)
	{
		foreach ($data as $r) {
			if ($r['type'] == 'default') {
				return $r[$key];
			}
		}
	}

	private function formatItem ($data)
	{
		return [
			'id' => $data['id'],//商品ID
			'name' => $data['data']['texts']['name1'],//商品名称
			'images' => array_column($data['data']['images']['all'], 'url'),//商品图片
			'category_id' => array_column($data['data']['defaultCategories'], 'id'),//所处分类
			'country' => $data['data']['item']['producingCountry']['names']['name'],//原产国
			'manufacturer' => $data['data']['item']['manufacturer']['name'],//供应商
			'min_num' => $this->getSalesPrices($data['data']['salesPrices'], 'minimumOrderQuantity'),//最低购买量

			'main_price' => $data['data']['prices']['default']['price']['value'],//价格
			'format_main_price' => $data['data']['prices']['default']['price']['formatted'],//格式化价格

			'discount_price' => $data['data']['prices']['default']['price']['value'],//价格
			'format_discount_price' => $data['data']['prices']['default']['price']['formatted'],//格式化价格

			'currency' => $data['data']['prices']['default']['currency'],//货币
			'stock' => $data['data']['stock']['net'],//库存
			'desc' => $data['data']['texts']['description'],//商品描述
			'short_desc' => $data['data']['texts']['shortDescription'],//短描述
			'unit' => $data['data']['unit']['names']['name'],//单位
			'vat' => $data['data']['prices']['default']['vat']['value'],//增值税
		];
	}

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
