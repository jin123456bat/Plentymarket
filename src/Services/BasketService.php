<?php

namespace Plentymarket\Services;

use Plenty\Modules\Authorization\Services\AuthHelper;
use Plenty\Modules\Basket\Contracts\BasketItemRepositoryContract;
use Plenty\Modules\Basket\Contracts\BasketRepositoryContract;
use Plenty\Modules\Basket\Exceptions\BasketCheckException;
use Plenty\Modules\Basket\Models\Basket;
use Plenty\Modules\Basket\Models\BasketItem;
use Plenty\Modules\Item\Variation\Contracts\VariationRepositoryContract;
use Plenty\Modules\Item\Variation\Models\Variation;
use Plenty\Modules\Item\VariationDescription\Contracts\VariationDescriptionRepositoryContract;
use Plenty\Modules\Item\VariationDescription\Models\VariationDescription;
use Plentymarket\Helper\Utils;

/**
 * Class BasketService
 * @package Plentymarket\Services
 */
class BasketService
{
	/**
	 * @var BasketItemRepositoryContract
	 */
	private $basketItemRepositoryContract;

	/**
	 * @var BasketRepositoryContract
	 */
	private $basketRepositoryContract;

	/**
	 * BasketService constructor.
	 * @param BasketItemRepositoryContract $basketItemRepositoryContract
	 * @param BasketRepositoryContract $basketRepositoryContract
	 */
	function __construct (BasketItemRepositoryContract $basketItemRepositoryContract, BasketRepositoryContract $basketRepositoryContract)
	{
		$this->basketItemRepositoryContract = $basketItemRepositoryContract;
		$this->basketRepositoryContract = $basketRepositoryContract;
	}

	/**
	 * 获取当前用户的购物车列表
	 */
	function getAll ()
	{
		$result = $this->basketItemRepositoryContract->all();
		return json_decode(json_encode($result), true);
	}

	/**
	 * List the basket items for order
	 *
	 * @return array
	 */
	public function getBasketItemsForOrder (): array
	{
		$basketItems = $this->getAll();
		$basketItemData = $this->getOrderItemData($basketItems);
		$basketItems = $this->addVariationData($basketItems, $basketItemData);

		return $basketItems;
	}

	private function getSortedBasketItemOrderParams ($basketItem): array
	{
		$newParams = [];
		if (!array_key_exists('basketItemOrderParams', $basketItem)) {
			return [];
		}

		foreach ($basketItem['basketItemOrderParams'] ?? [] as $param) {
			$propertyId = (int)$param['propertyId'];

			foreach ((array)$basketItem['variation']['data']['properties'] as $property) {
				if ($property['property']['id'] === $propertyId) {
					$newParam = $param;
					$newParam['position'] = $property['property']['position'];
					$newParams[] = $newParam;
				}
			}
		}

		usort(
			$newParams,
			function ($documentA, $documentB) {
				return $documentA['position'] - $documentB['position'];
			}
		);

		return $newParams;
	}

	/**
	 * Load the variation data for the basket item
	 *
	 * @param BasketItem[] $basketItems
	 * @param array $basketItemData
	 * @param boolean $sortOrderItems
	 *
	 * @return array
	 */
	private function addVariationData ($basketItems, $basketItemData, $sortOrderItems = false): array
	{
		$showNetPrice = pluginApp(AccountService::class)->showNetPrices();

		$result = [];
		foreach ($basketItems as &$basketItem) {
			if ($showNetPrice) {
				$basketItem['price'] = round($basketItem['price'] * 100 / (100.0 + $basketItem['vat']), 2);
			}

			if (array_key_exists($basketItem['variationId'], $basketItemData)) {
				$basketItem["variation"] = $basketItemData[$basketItem['variationId']];
			} else {
				$basketItem["variation"] = null;
			}

			if ($sortOrderItems && array_key_exists($basketItem['variationId'], $basketItemData)) {
				$basketItem['basketItemOrderParams'] = $this->getSortedBasketItemOrderParams($basketItem);
			}

			array_push(
				$result,
				$basketItem
			);
		}
		return $result;
	}

	/**
	 * Get the data of the basket items
	 * @param BasketItem[] $basketItems
	 * @return array
	 */
	private function getOrderItemData ($basketItems = array()): array
	{
		if (count($basketItems) <= 0) {
			return array();
		}

		/**
		 * @var VariationRepositoryContract $variationRepository
		 */
		$variationRepository = pluginApp(VariationRepositoryContract::class);

		/**
		 * @var VariationDescriptionRepositoryContract $variationDescriptionRepository
		 */
		$variationDescriptionRepository = pluginApp(VariationDescriptionRepositoryContract::class);

		$lang = Utils::getLang();

		/** @var AuthHelper $authHelper */
		$authHelper = pluginApp(AuthHelper::class);

		$result = [];
		foreach ($basketItems as $basketItem) {
			/**
			 * @var Variation $variation
			 */
			$variation = $variationRepository->findById($basketItem['variationId']);

			/**
			 * @var VariationDescription $texts
			 */
			$texts = $authHelper->processUnguarded(function () use ($variationDescriptionRepository, $basketItem, $lang) {
				//try {
				return $variationDescriptionRepository->find($basketItem['variationId'], $lang);
				//} catch (\Throwable $e) {
				//	return '';
				//}
			});

			$result[$basketItem['variationId']]['data']['variation']['name'] = $variation->name ?? '';
			$result[$basketItem['variationId']]['data']['texts']['name1'] = $texts->name ?? '';
			$result[$basketItem['variationId']]['data']['texts']['name2'] = $texts->name2 ?? '';
			$result[$basketItem['variationId']]['data']['texts']['name3'] = $texts->name3 ?? '';
			$result[$basketItem['variationId']]['data']['variation']['vatId'] = $variation->vatId ?? $variation->parent->vatId;
			$result[$basketItem['variationId']]['data']['properties'] = $variation->variationProperties->toArray();
			$result[$basketItem['variationId']]['data']['basketItemOrderParams'] = $basketItem['basketItemOrderParams'];
		}

		return $result;
	}

	/**
	 * 添加到购物车
	 * @param $data
	 */
	function create ($data)
	{
		$data['referrerId'] = $this->getBasket()->referrerId;
		$basketItem = $this->basketItemRepositoryContract->findExistingOneByData($data);
		if ($basketItem instanceof BasketItem) {
			$data['id'] = $basketItem->id;
			$data['quantity'] = (float)$data['quantity'] + $basketItem->quantity;
			$this->update($basketItem->id, $data);
		} else {
			$this->basketItemRepositoryContract->addBasketItem($data);
		}
	}

	/**
	 * 根据ID更新数量
	 * @param int $basketItemId
	 * @param int $quantity
	 * @return bool
	 * @throws \Exception
	 */
	function updateQuantity (int $basketItemId, int $quantity): bool
	{
		$basketItem = $this->basketItemRepositoryContract->findOneById($basketItemId);
		if ($basketItem instanceof BasketItem) {
			$data = [
				'id' => $basketItemId,
				'variationId' => $basketItem->variationId,
				'referrerId' => $this->getBasket()->referrerId,
				'quantity' => $quantity
			];
			return $this->update($basketItemId, $data);
		}
		return false;
	}

	/**
	 * 更新购物车
	 * @param int $basketItemId
	 * @param array $data
	 * @return bool 成功返回true 失败返回false
	 */
	function update (int $basketItemId, array $data): bool
	{
		try {
			$this->basketItemRepositoryContract->updateBasketItem($basketItemId, $data);
			return true;
		} catch (BasketCheckException $e) {
			return false;
		}
	}

	/**
	 * 删除购物车中的物品
	 * @param int $basketItemId
	 * @param bool $dispatchAfterBasketChangedEvent
	 */
	function delete (int $basketItemId, bool $dispatchAfterBasketChangedEvent = true): void
	{
		$this->basketItemRepositoryContract->removeBasketItem($basketItemId, $dispatchAfterBasketChangedEvent);
	}

	/**
	 * @return Basket
	 */
	function getBasket ()
	{
		return $this->basketRepositoryContract->load();
	}

}
