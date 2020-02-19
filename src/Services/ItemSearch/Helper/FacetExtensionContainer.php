<?php

namespace Plentymarket\Services\ItemSearch\Helper;

use Plenty\Plugin\Events\Dispatcher;
use Plentymarket\Services\ItemSearch\Contracts\FacetExtension;

/**
 * Class FacetExtensionContainer
 * @package Plentymarket\Services\ItemSearch\Helper
 */
class FacetExtensionContainer
{
	/**
	 * @var FacetExtension[]
	 */
	private $facetExtensionsList = [];

	/**
	 * @var Dispatcher
	 */
	private $dispatcher;

	/**
	 * FacetExtensionContainer constructor.
	 * @param Dispatcher $dispatcher
	 */
	public function __construct (Dispatcher $dispatcher)
	{
		$this->dispatcher = $dispatcher;
	}

	/**
	 * @return FacetExtension[]
	 */
	public function getFacetExtensions ()
	{
		if (empty($this->facetExtensionsList)) {
			$this->dispatcher->fire('Plentymarket.initFacetExtensions', [$this]);
		}

		return $this->facetExtensionsList;
	}

	/**
	 * @param FacetExtension $facetExtension
	 */
	public function addFacetExtension (FacetExtension $facetExtension)
	{
		$this->facetExtensionsList[] = $facetExtension;
	}
}
