<?php

namespace Plentymarket\Services\UrlBuilder;

use Plenty\Plugin\Log\Loggable;
use Plentymarket\Helper\Utils;
use Plentymarket\Services\CategoryService;

class CategoryUrlBuilder
{
	use Loggable;

	public function buildUrl (int $categoryId, string $lang = null, int $webstoreId = null): UrlQuery
	{
		if ($lang === null) {
			$lang = Utils::getLang();
		}

		/** @var CategoryService $categoryService */
		$categoryService = pluginApp(CategoryService::class);
		$category = $categoryService->get($categoryId, $lang);

		if ($category !== null) {
			if (is_null($webstoreId)) {
				$webstoreId = Utils::getWebstoreId();
			}

			return $this->buildUrlQuery(
				$categoryService->getURL($category, $lang, $webstoreId),
				$lang
			);
		}

		$this->getLogger(__CLASS__)->error(
			'Plentymarket::Debug.CategoryUrlBuilder_categoryNotFound',
			[
				'categoryId' => $categoryId,
				'lang' => $lang
			]
		);
		return $this->buildUrlQuery('', $lang);
	}

	private function buildUrlQuery ($path, $lang): UrlQuery
	{
		if (substr($path, 0, 4) === '/' . $lang . '/') {
			// FIX: category url already contains language, if it is different to default language
			$path = substr($path, 4);
		}
		return pluginApp(
			UrlQuery::class,
			['path' => $path, 'lang' => $lang]
		);
	}
}
