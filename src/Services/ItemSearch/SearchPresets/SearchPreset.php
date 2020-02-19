<?php

namespace Plentymarket\Services\ItemSearch\SearchPresets;

/**
 * Interface SearchPreset
 * Define a preset of a search factory.
 * @package Plentymarket\Services\ItemSearch\SearchPresets
 */
interface SearchPreset
{
	/**
	 * Get the search factory from the preset.
	 * @param array $options
	 * @return mixed
	 */
	public static function getSearchFactory (array $options);
}
