<?php

namespace Plentymarket\Services;

use Plentymarket\Services\ItemSearch\SearchPresets\CategoryItems;
use Plentymarket\Services\ItemSearch\Services\ItemSearchService;

class ItemListService
{
	public function getItemList ($id = null, $sorting = null, $maxItems = 0)
	{
		/** @var ItemSearchService $searchService */
		$searchService = pluginApp(ItemSearchService::class);

		$searchFactory = CategoryItems::getSearchFactory([
			'categoryId' => $id,
			'sorting' => $sorting
		]);

		if ($maxItems > 0) {
			$searchFactory->setPage(1, $maxItems);
		}

		return $searchService->getResult($searchFactory);
	}
}
