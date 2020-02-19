<?php

namespace Plentymarket\Services\ItemSearch\SearchPresets;

use Plentymarket\Services\ItemSearch\Factories\VariationSearchFactory;
use Plentymarket\Services\ItemSearch\Helper\ResultFieldTemplate;

/**
 * Class BasketItems
 *
 * Search preset for basket items.
 * Available options:
 * - variationIds: Ids of basket items to get data for
 * - quantities:   Quantity of each item to be considered when searching prices
 *
 * @package Plentymarket\Services\ItemSearch\SearchPresets
 */
class BasketItems implements SearchPreset
{
	/**
	 * @inheritdoc
	 */
	public static function getSearchFactory (array $options)
	{
		$variationIds = $options['variationIds'];
		$quantities = $options['quantities'];

		/** @var VariationSearchFactory $searchFactory */
		$searchFactory = pluginApp(VariationSearchFactory::class);
		$searchFactory->withResultFields(
			ResultFieldTemplate::load(ResultFieldTemplate::TEMPLATE_BASKET_ITEM)
		);

		$searchFactory
			->withLanguage()
			->withUrls()
			->withImages()
			->withPropertyGroups()
			->withOrderPropertySelectionValues()
			->withDefaultImage()
			->withBundleComponents()
			->isVisibleForClient()
			->isActive()
			->hasVariationIds($variationIds)
			->setPage(1, count($variationIds))
			->withReducedResults();

		if (!is_null($quantities)) {
			$searchFactory->withPrices($quantities);
		}

		return $searchFactory;
	}
}
