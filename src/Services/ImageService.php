<?php

namespace Plentymarket\Services;

use Plenty\Modules\Item\Variation\Contracts\VariationImageServiceContract;

/**
 * Class ImageService
 * @package Plentymarket\Services
 */
class ImageService
{
	/**
	 * @var VariationImageServiceContract
	 */
	private $variationImageServiceContract;

	/**
	 * ImageService constructor.
	 * @param VariationImageServiceContract $variationImageServiceContract
	 */
	function __construct (VariationImageServiceContract $variationImageServiceContract)
	{
		$this->variationImageServiceContract = $variationImageServiceContract;
	}

	/**
	 * @return array
	 */
	function getAll (): array
	{
		return $this->variationImageServiceContract->getAll();
	}

	/**
	 * @param int $itemId
	 * @param int|null $imageId
	 * @return mixed
	 */
	function getData (int $itemId, int $imageId = null)
	{
		return $this->variationImageServiceContract->getData($itemId, $imageId);
	}
}
