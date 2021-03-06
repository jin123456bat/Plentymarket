<?php

namespace Plentymarket\Services\ItemSearch\SearchPresets;

use Plentymarket\Services\ItemSearch\Factories\VariationSearchFactory;
use Plentymarket\Services\ItemSearch\Helper\ResultFieldTemplate;
use Plentymarket\Services\ItemSearch\Helper\SortingHelper;

/**
 * Class CategoryItems
 *
 * Search preset for category items.
 * Available options:
 * - categoryId:    Category id to get variations for
 * - facets:        Active facets to filter variations by
 * - sorting:       Configuration value from plugin config
 * - page:          Current page
 * - itemsPerPage:  Number of items per page
 * - priceMin:      Minimum price of the variations
 * - priceMax       Maximum price of the variations
 *
 * @package Plentymarket\Services\ItemSearch\SearchPresets
 */
class CategoryItems implements SearchPreset
{
	/**
	 * @param array $options
	 * @return VariationSearchFactory
	 */
	public static function getSearchFactory (array $options)
	{
		$categoryId = $options['categoryId'] ?? null;
		$itemId = $options['itemId'] ?? null;
		$itemIds = $options['itemIds'] ?? [];
		$facets = $options['facets'];
		$query = $options['query'] ?? null;

		$variationIds = $options['variationIds'] ?? null;

		$sorting = SortingHelper::getCategorySorting($options['sorting']);

		$priceMin = 0;
		$priceMax = 0;

		/** @var VariationSearchFactory $searchFactory */
		$searchFactory = pluginApp(VariationSearchFactory::class);

		$searchFactory->withResultFields(ResultFieldTemplate::load(ResultFieldTemplate::TEMPLATE_LIST_ITEM))
			->hasVariationIds($variationIds)
			->hasItemId($itemId)
			->hasItemIds($itemIds)
			->withLanguage()
			->withImages()
			->withPrices()
			->withDefaultImage()
			->isInCategory($categoryId)
//			->isVisibleForClient()
//			->isActive()
//			->isSalable()
//			->isHiddenInCategoryList(false)
			->hasNameInLanguage()
			->hasPriceForCustomer()
			->hasPriceInRange($priceMin, $priceMax)
			->hasFacets($facets)
			->sortByMultiple($sorting)
			->groupByTemplateConfig()
			->withLinkToContent()
			->withGroupedAttributeValues()
			->hasNameString($query)
			->hasSearchString($query)
			->withReducedResults();

		return $searchFactory;
	}
}
