<?php

namespace Plentymarket\Services\ItemSearch\Extensions;

use Plenty\Modules\Category\Contracts\CategoryRepositoryContract;
use Plentymarket\Helper\Utils;
use Plentymarket\Services\CategoryService;
use Plentymarket\Services\ItemSearch\Factories\VariationSearchFactory;

/**
 * Class CurrentCategoryExtension
 *
 * Set current category when loading an item to be displayed in breadcrumbs or navigation templates.
 *
 * @package Plentymarket\Services\ItemSearch\Extensions
 */
class CurrentCategoryExtension implements ItemSearchExtension
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
				VariationSearchFactory::INHERIT_MUTATORS,
				VariationSearchFactory::INHERIT_PAGINATION,
				VariationSearchFactory::INHERIT_COLLAPSE,
				VariationSearchFactory::INHERIT_AGGREGATIONS,
				VariationSearchFactory::INHERIT_SORTING
			])
			->withResultFields([
				'item.id',
				'variation.id',
				'texts.*',
				'defaultCategories'
			])
			->build();
	}

	/**
	 * @inheritdoc
	 */
	public function transformResult ($baseResult, $extensionResult)
	{
		if (count($extensionResult['documents'])) {
			$data = $extensionResult['documents'][0]['data'];
			$defaultCategories = $data['defaultCategories'];

			if (count($defaultCategories)) {
				$currentCategoryId = 0;
				foreach ($defaultCategories as $defaultCategory) {
					if ((int)$defaultCategory['plentyId'] == Utils::getPlentyId()) {
						$currentCategoryId = $defaultCategory['id'];
					}
				}
				if ((int)$currentCategoryId > 0) {
					/**
					 * @var CategoryRepositoryContract $categoryRepo
					 */
					$categoryRepo = pluginApp(CategoryRepositoryContract::class);
					$currentCategory = $categoryRepo->get($currentCategoryId, Utils::getLang());

					/**
					 * @var CategoryService $categoryService
					 */
					$categoryService = pluginApp(CategoryService::class);
					$categoryService->setCurrentCategory($currentCategory);
					$categoryService->setCurrentItem($data);
				}
			}
		}

		return $baseResult;
	}
}
