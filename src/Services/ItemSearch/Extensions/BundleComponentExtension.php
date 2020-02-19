<?php

namespace Plentymarket\Services\ItemSearch\Extensions;

use Plenty\Modules\Authorization\Services\AuthHelper;
use Plenty\Modules\Item\VariationBundle\Contracts\VariationBundleRepositoryContract;
use Plentymarket\Services\ItemSearch\Factories\VariationSearchFactory;
use Plentymarket\Services\ItemSearch\SearchPresets\BasketItems;
use Plentymarket\Services\ItemSearch\Services\ItemSearchService;

class BundleComponentExtension implements ItemSearchExtension
{
	/**
	 * @inheritdoc
	 */
	public function getSearch ($parentSearchBuilder)
	{
		return VariationSearchFactory::inherit(
			$parentSearchBuilder,
			[
				VariationSearchFactory::INHERIT_FILTERS,
				VariationSearchFactory::INHERIT_PAGINATION,
				VariationSearchFactory::INHERIT_COLLAPSE,
				VariationSearchFactory::INHERIT_AGGREGATIONS,
				VariationSearchFactory::INHERIT_SORTING
			])
			->withResultFields([
				'variation.bundleType',
				'variation.id'
			])
			->build();
	}

	/**
	 * @inheritdoc
	 */
	public function transformResult ($baseResult, $extensionResult)
	{
		foreach ($extensionResult['documents'] as $key => $extensionDocument) {
			$document = $extensionResult['documents'][$key];
			if (count($extensionDocument)
				&& count($extensionDocument['data']['variation'])
				&& $extensionDocument['data']['variation']['bundleType'] === 'bundle') {
				/** @var AuthHelper $authHelper */
				$authHelper = pluginApp(AuthHelper::class);
				$variationId = $document['data']['variation']['id'];
				$bundle = $authHelper->processUnguarded(
					function () use ($variationId) {
						/** @var VariationBundleRepositoryContract $bundleRepository */
						$bundleRepository = pluginApp(VariationBundleRepositoryContract::class);
						return $bundleRepository->findByVariationId($variationId);
					}
				);

				$bundleVariationIds = [];
				$bundleQuantities = [];
				foreach ($bundle as $bundleComponent) {
					$bundleVariationIds[] = $bundleComponent->componentVariationId;
					$bundleQuantities[$bundleComponent->componentVariationId] = $bundleComponent->componentQuantity;
				}

				/** @var ItemSearchService $itemSearchService */
				$itemSearchService = pluginApp(ItemSearchService::class);
				$bundleVariations = $itemSearchService->getResult(
					BasketItems::getSearchFactory([
						'variationIds' => $bundleVariationIds
					])
				);

				$bundleComponents = [];
				foreach ($bundleVariations['documents'] as $bundleVariation) {
					$bundleComponents[] = [
						'quantity' => $bundleQuantities[$bundleVariation['id']],
						'data' => $bundleVariation['data']
					];
				}

				$baseResult['documents'][$key]['data']['bundleComponents'] = $bundleComponents;
			}
		}

		return $baseResult;
	}
}
