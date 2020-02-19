<?php

namespace Plentymarket\Services\ItemSearch\Services;

use Plentymarket\Helper\DefaultSearchResult;
use Plentymarket\Services\ItemSearch\Factories\BaseSearchFactory;
use Plentymarket\Services\ItemSearch\Factories\MultiSearchFactory;

/**
 * Class ItemSearchService
 *
 * Execute elastic search requests.
 *
 * @package Plentymarket\Services\ItemSearch\Services
 */
class ItemSearchService
{
	/**
	 * Get result of a single search factory;
	 *
	 * @param BaseSearchFactory $searchFactory The factory to get results for.
	 *
	 * @return array
	 */
	public function getResult ($searchFactory)
	{
		return $this->getResults([$searchFactory])[0];
	}

	/**
	 * @param array $searches Map of search factories to execute.
	 * @return array Results of multisearch request. Keys will be used from input search map.
	 */
	public function getResults ($searches)
	{
		/** @var MultiSearchFactory $multiSearchFactory */
		$multiSearchFactory = pluginApp(MultiSearchFactory::class);

		foreach ($searches as $resultName => $search) {
			$multiSearchFactory->addSearch($resultName, $search);
		}
		$results = $multiSearchFactory->getResults();

		foreach ($results as $resultName => $result) {
			$results[$resultName] = $this->normalizeResult($result);
		}

		return $results;
	}

	private function normalizeResult ($result)
	{
		if (count($result['documents'])) {
			foreach ($result['documents'] as $key => $variation) {
				$result['documents'][$key]['data'] = DefaultSearchResult::merge($variation['data']);
			}
		}

		return $result;
	}
}
