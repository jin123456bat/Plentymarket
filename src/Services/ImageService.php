<?php

namespace Plentymarket\Services;

use Plenty\Modules\Item\Variation\Contracts\VariationImageServiceContract;

class ImageService
{
	private $variationImageServiceContract;

	function __construct (VariationImageServiceContract $variationImageServiceContract)
	{
		$this->variationImageServiceContract = $variationImageServiceContract;
	}

	function getAll (): array
	{
		return $this->variationImageServiceContract->getAll();
	}

	function getData (int $itemId, int $imageId = null)
	{
		return $this->variationImageServiceContract->getData($itemId, $imageId);
	}
}
