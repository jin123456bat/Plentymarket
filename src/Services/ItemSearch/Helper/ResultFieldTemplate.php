<?php

namespace Plentymarket\Services\ItemSearch\Helper;

use Plenty\Plugin\Events\Dispatcher;

/**
 * Class ResultFieldTemplate
 * Emit events to request templates to be used for result fields.
 * @package Plentymarket\Services\ItemSearch\Helper
 */
class ResultFieldTemplate
{
	use LoadResultFields;

	const TEMPLATE_LIST_ITEM = 'Plentymarket.ResultFields.ListItem';
	const TEMPLATE_SINGLE_ITEM = 'Plentymarket.ResultFields.SingleItem';
	const TEMPLATE_BASKET_ITEM = 'Plentymarket.ResultFields.BasketItem';
	const TEMPLATE_AUTOCOMPLETE_ITEM_LIST = 'Plentymarket.ResultFields.AutoCompleteListItem';
	const TEMPLATE_CATEGORY_TREE = 'Plentymarket.ResultFields.CategoryTree';
	const TEMPLATE_VARIATION_ATTRIBUTE_MAP = 'Plentymarket.ResultFields.VariationAttributeMap';

	private $templates = [];
	private $requiredFields = [];

	/**
	 * Get the path to result fields file from template/ theme
	 * @param string $template Event to be emitted to templates/ themes
	 * @return string
	 */
	public static function get ($template)
	{
		$container = self::init($template);
		return $container->templates[$template];
	}

	private static function init ($template)
	{
		/** @var Dispatcher $dispatcher */
		$dispatcher = pluginApp(Dispatcher::class);

		/** @var ResultFieldTemplate $container */
		$container = pluginApp(ResultFieldTemplate::class);
		$dispatcher->fire($template, [$container]);

		return $container;
	}

	public static function load ($template)
	{
		$container = self::init($template);
		$resultFields = $container->loadResultFields($container->templates[$template]);
		foreach ($container->requiredFields[$template] ?? [] as $requiredField) {
			foreach ($resultFields as $resultField) {
				$isWildcard = substr($resultField, strlen($resultField) - 1, 1) === "*";
				$includesField = strpos($requiredField, substr($resultField, 0, strlen($resultField) - 1)) === 0;
				if ($resultField === $requiredField || ($isWildcard && $includesField)) {
					break;
				}
			}
			$resultFields[] = $requiredField;
		}

		return $resultFields;
	}
}
