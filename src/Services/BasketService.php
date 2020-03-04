<?php

namespace Plentymarket\Services;

use Plenty\Modules\Basket\Contracts\BasketItemRepositoryContract;
use Plenty\Modules\Basket\Contracts\BasketRepositoryContract;
use Plenty\Modules\Basket\Exceptions\BasketCheckException;
use Plenty\Modules\Basket\Models\Basket;
use Plenty\Modules\Basket\Models\BasketItem;

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
	function getAll (): array
	{
		return $this->basketItemRepositoryContract->all();
	}

	/**
	 * 添加到购物车
	 * @param $data
	 * @return bool 成功返回true 失败返回false
	 */
	function create ($data): bool
	{
		$data['referrerId'] = $this->getBasket()->referrerId;
		$basketItem = $this->basketRepositoryContract->findExistingOneByData($data);
		if ($basketItem instanceof BasketItem) {
			$data['id'] = $basketItem['id'];
			$data['quantity'] = (float)$data['quantity'] + $basketItem->quantity;
			return $this->update($basketItem->id, $data);
		} else {
			$basketItem = $this->basketItemRepositoryContract->addBasketItem($data);
			if ($basketItem instanceof BasketItem) {
				return true;
			}
			return false;
		}
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
