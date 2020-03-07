<?php

namespace Plentymarket\Extensions;

use Plenty\Plugin\Templates\Extensions\Twig_Extension;
use Plenty\Plugin\Templates\Factories\TwigFactory;

/**
 * Provide services and helper functions to twig engine
 * Class TwigServiceProvider
 * @package Plentymarket\Extensions
 */
class TwigServiceProvider extends Twig_Extension
{
	private $twigFactory;

	public function __construct (TwigFactory $twigFactory)
	{
		$this->twigFactory = $twigFactory;
	}

	/**
	 * Return the name of the extension. The name must be unique.
	 *
	 * @return string The name of the extension
	 */
	public function getName (): string
	{
		return "Plentymarket_Extension_TwigServiceProvider";
	}

	/**
	 * Return a list of filters to add.
	 *
	 * @return array The list of filters to add.
	 */
	public function getFilters (): array
	{
		return [
			$this->twigFactory->createSimpleFilter('ceil', function ($value) {
				return ceil($value);
			})
		];
	}

	/**
	 * Return a list of functions to add.
	 *
	 * @return array the list of functions to add.
	 */
	public function getFunctions (): array
	{
		return [
			$this->twigFactory->createSimpleFunction('json_encode', function ($value) {
				return json_encode($value);
			})
		];
	}

	/**
	 * Return a map of global helper objects to add.
	 *
	 * @return array the map of helper objects to add.
	 */
	public function getGlobals (): array
	{
		return [
			"services" => pluginApp(TwigServiceContainer::class)
		];
	}
}
