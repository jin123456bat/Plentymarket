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

	private function getSalesPrices (array $data, string $key)
	{
		foreach ($data as $r) {
			if ($r['type'] == 'default') {
				return $r[$key];
			}
		}
	}

	private function formatItemsList ($data)
	{
		$list = [];
		foreach ($data['documents'] as $item) {
			$list[] = [
				'id' => $item['id'],//商品ID
				'name' => $item['data']['texts']['name1'],//商品名称
				'images' => array_column($item['data']['images']['all'], 'url'),//商品图片
				'category_id' => array_column($item['data']['defaultCategories'], 'id'),//所处分类
				'country' => $item['data']['item']['producingCountry']['names']['name'],//原产国
				'manufacturer' => $item['data']['item']['manufacturer']['name'],//供应商
				'min_num' => $this->getSalesPrices($item['data']['salesPrices'], 'minimumOrderQuantity'),//最低购买量

				'main_price' => $item['data']['prices']['default']['price']['value'],//价格
				'format_main_price' => $item['data']['prices']['default']['price']['formatted'],//格式化价格

				'discount_price' => $item['data']['prices']['default']['price']['value'],//价格
				'format_discount_price' => $item['data']['prices']['default']['price']['formatted'],//格式化价格

				'currency' => $item['data']['prices']['default']['currency'],//货币
				'stock' => $item['data']['stock']['net'],//库存
				'desc' => $item['data']['texts']['description'],//商品描述
				'unit' => $item['data']['unit']['names']['name'],//单位
				'vat' => $item['data']['prices']['default']['vat']['value'],//增值税
			];
		}

		return [
			'total' => $data['total'],//总数
			'list' => $list,//列表
		];
	}
}
