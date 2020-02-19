<?php

namespace Plentymarket\Services\ItemSearch\Helper;

use Plentymarket\Services\ConfigService;
use Plentymarket\Services\ItemSearch\Factories\BaseSearchFactory;

/**
 * Class SortingHelper
 *
 * Generate sorting values from plugin configuration.
 *
 * @package Plentymarket\Services\ItemSearch\Helper
 */
class SortingHelper
{
	/**
	 * Get sorting values for categories from config
	 *
	 * @param string $sortingConfig The configuration value
	 * @return array
	 */
	public static function getCategorySorting ($sortingConfig = null)
	{
		return self::getSorting($sortingConfig, true);
	}

	/**
	 * Get sorting values from plugin configuration
	 *
	 * @param string $sortingConfig The configuration value from plugin
	 * @param bool $isCategory Get default sorting configuration for category or for search
	 *
	 * @return array
	 */
	public static function getSorting ($sortingConfig = null, $isCategory = true)
	{
		$sortings = [];
		if ($sortingConfig === 'default.recommended_sorting' || !strlen($sortingConfig)) {
			$configService = pluginApp(ConfigService::class);
			$configKeyPrefix = $isCategory ? 'sorting.priorityCategory' : 'sorting.prioritySearch';

			foreach ([1, 2, 3] as $priority) {
				$defaultSortingValue = $configService->getTemplateConfig($configKeyPrefix . $priority);
				if ($defaultSortingValue !== 'notSelected') {
					$defaultSorting = self::getSorting($defaultSortingValue, $isCategory);
					$sortings[] = $defaultSorting[0];
				}
			}
		} else {
			list($sortingField, $sortingOrder) = explode('_', $sortingConfig);
			if ($sortingField === 'item.score') {
				$sortingField = '_score';
				$sortingOrder = BaseSearchFactory::SORTING_ORDER_DESC;
			} else if ($sortingField === 'texts.name') {
				$sortingField = self::getUsedItemName();
			}

			$sortings[] = ['field' => $sortingField, 'order' => $sortingOrder];
		}

		return $sortings;
	}

	/**
	 * @return string
	 */
	public static function getUsedItemName ()
	{
		$configService = pluginApp(ConfigService::class);
		$usedItemNameIndex = $configService->getTemplateConfig('item.name');

		$usedItemName = [
			'texts.name1',
			'texts.name2',
			'texts.name3'
		][$usedItemNameIndex];

		return $usedItemName;
	}

	/**
	 * Get sorting values for searches from config
	 *
	 * @param string $sortingConfig The configuration value
	 * @return array
	 */
	public static function getSearchSorting ($sortingConfig = null)
	{
		return self::getSorting($sortingConfig, false);
	}

	public static function splitPathAndOrder ($sorting)
	{
		$e = explode('_', $sorting);

		$sorting = [
			'path' => $e[0],
			'order' => $e[1]
		];

		if ($sorting['path'] == 'texts.name') {
			$sorting['path'] = self::getUsedItemName();
		}

		return $sorting;
	}
}
