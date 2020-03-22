<?php //strict

namespace Plentymarket\Builder\Order;

use Plenty\Plugin\Application;
use Plentymarket\Services\BasketService;

/**
 * Class OrderBuilder
 * @package Plentymarket\Builder\Order
 */
class OrderBuilder
{
	/**
	 * @var Application
	 */
	private $app;
	/**
	 * @var BasketService
	 */
	private $basketService;

	public function __construct (Application $app, BasketService $basketService)
	{
		$this->app = $app;
		$this->basketService = $basketService;
	}

	public function prepare (int $type, int $plentyId = 0): OrderBuilderQuery
	{
		if ($plentyId == 0) {
			$plentyId = $this->app->getPlentyId();
		}

		$instance = $this->app->make(OrderBuilderQuery::class,
			[
				"app" => $this->app,
				"basketService" => $this->basketService,
				"type" => (int)$type,
				"plentyId" => $plentyId
			]
		);

		if (!$instance instanceof OrderBuilderQuery) {
			throw new \Exception('Error while instantiating OrderBuilderQuery');
		}
		return $instance;
	}
}
