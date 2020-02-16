<?php

namespace Plentymarket\Services;

use Plenty\Modules\Basket\Contracts\BasketItemRepositoryContract;
use Plenty\Modules\Basket\Exceptions\BasketCheckException;
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
	 * BasketService constructor.
	 * @param BasketItemRepositoryContract $basketItemRepositoryContract
	 */
	function __construct (BasketItemRepositoryContract $basketItemRepositoryContract)
	{
		$this->basketItemRepositoryContract = $basketItemRepositoryContract;
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
		try {
			$basketItem = $this->basketItemRepositoryContract->addBasketItem($data);
			if ($basketItem instanceof BasketItem) {
				return true;
			}
			return false;
		} catch (BasketCheckException $e) {
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

}
