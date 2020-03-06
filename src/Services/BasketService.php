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
	function getAll ()
	{
		return $this->basketItemRepositoryContract->all();
	}

	/**
	 * 添加到购物车
	 * @param $data
	 * @return BasketItem
	 * @throws \Exception
	 */
	function create ($data): BasketItem
	{
		$data['referrerId'] = $this->getBasket()->referrerId;
		$basketItem = $this->basketItemRepositoryContract->findExistingOneByData($data);
		if ($basketItem instanceof BasketItem) {
			$data['id'] = $basketItem->id;
			$data['quantity'] = (float)$data['quantity'] + $basketItem->quantity;
			if ($this->update($basketItem->id, $data)) {
				return $basketItem;
			}
		} else {
			$basketItem = $this->basketItemRepositoryContract->addBasketItem($data);
			if ($basketItem instanceof BasketItem) {
				return $basketItem;
			}
		}
		throw new \Exception('添加购物车失败');
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
