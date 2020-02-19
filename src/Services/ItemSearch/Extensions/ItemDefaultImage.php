<?php

namespace Plentymarket\Services\ItemSearch\Extensions;

use Plenty\Modules\Item\ItemImage\Contracts\ItemImageSettingsRepositoryContract;

class ItemDefaultImage implements ItemSearchExtension
{
	private $allImagesRequested = false;
	private $itemImagesRequested = false;
	private $variationImagesRequested = false;

	/**
	 * @inheritdoc
	 */
	public function getSearch ($parentSearchBuilder)
	{
		$resultFields = $parentSearchBuilder->getResultFields();

		$this->allImagesRequested = $this->isImageRequested($resultFields, 'all');
		$this->itemImagesRequested = $this->isImageRequested($resultFields, 'item');
		$this->variationImagesRequested = $this->isImageRequested($resultFields, 'variation');
	}

	private function isImageRequested ($resultFields, $imageField = 'all')
	{
		if (is_null($resultFields) || count($resultFields) <= 0) {
			// All fields are requested
			return true;
		}

		$fieldPrefix = "images." . $imageField;

		foreach ($resultFields as $field) {
			if (substr($field, 0, strlen($fieldPrefix)) === $fieldPrefix) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @inheritdoc
	 */
	public function transformResult ($baseResult, $extensionResult)
	{
		foreach ($baseResult['documents'] as $key => $resultEntry) {
			if ($this->allImagesRequested) {
				$baseResult['documents'][$key]['data'] = $this->appendDefaultImage($resultEntry['id'], $resultEntry['data'], 'all');
			}

			if ($this->itemImagesRequested && !$this->allImagesRequested) {
				$baseResult['documents'][$key]['data'] = $this->appendDefaultImage($resultEntry['id'], $resultEntry['data'], 'item');
			}

			if ($this->variationImagesRequested && !$this->allImagesRequested) {
				$baseResult['documents'][$key]['data'] = $this->appendDefaultImage($resultEntry['id'], $resultEntry['data'], 'variation');
			}
		}
		return $baseResult;
	}

	private function appendDefaultImage ($id, $variationData, $imageField = 'all')
	{
		$defaultImage = [
			'urlMiddle' => $this->loadItemImageSettings()->placeholder['imagePlaceholderURL'],
			'url' => $this->loadItemImageSettings()->placeholder['imagePlaceholderURL'],
			'urlSecondPreview' => $this->loadItemImageSettings()->placeholder['previewImagePlaceholderURL'],
			'md5Checksum' => '',
			'itemId' => $id,
			'path' => $this->loadItemImageSettings()->placeholder['imagePlaceholderURL'],
			'createdAt' => date('Y-m-d H:i:s'),
			'cleanImageName' => '',
			'urlPreview' => $this->loadItemImageSettings()->placeholder['previewImagePlaceholderURL'],
			'size' => 0,
			'width' => 0,
			'id' => 0,
			'position' => 0,
			'fileType' => '',
			'md5ChecksumOriginal' => '',
			'updatedAt' => date('Y-m-d H:i:s'),
			'height' => 0
		];

		if (!array_key_exists('images', $variationData)) {
			$variationData['images'] = [];
		}

		if (!array_key_exists($imageField, $variationData['images']) || count($variationData['images'][$imageField]) <= 0) {
			$variationData['images'][$imageField] = [$defaultImage];
		}
		return $variationData;
	}

	private function loadItemImageSettings ()
	{
		/** @var ItemImageSettingsRepositoryContract $itemImageSettingsRepository */
		$itemImageSettingsRepository = pluginApp(ItemImageSettingsRepositoryContract::class);
		return $itemImageSettingsRepository->get();
	}
}
