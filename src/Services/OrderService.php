<?php

namespace Plentymarket\Services;

use Plenty\Modules\Order\Contracts\OrderAmountRepositoryContract;
use Plenty\Modules\Order\Contracts\OrderRepositoryContract;
use Plenty\Modules\Order\Models\Order;
use Plenty\Modules\Order\Models\OrderAmount;
use Plenty\Modules\Order\Status\Contracts\OrderStatusRepositoryContract;
use Plenty\Repositories\Models\PaginatedResult;

/**
 * Class OrderService
 * @package Plentymarket\Services
 */
class OrderService
{
	/**
	 * @var OrderRepositoryContract
	 */
	private $orderRepositoryContract;

	/**
	 * @var OrderAmountRepositoryContract
	 */
	private $orderAmountRepositoryContract;

	/**
	 * @var OrderStatusRepositoryContract
	 */
	private $orderStatusRepositoryContract;

	/**
	 * OrderService constructor.
	 * @param OrderRepositoryContract $orderRepositoryContract
	 * @param OrderStatusRepositoryContract $orderStatusRepositoryContract
	 */
	function __construct (OrderRepositoryContract $orderRepositoryContract, OrderAmountRepositoryContract $orderAmountRepositoryContract, OrderStatusRepositoryContract $orderStatusRepositoryContract)
	{
		$this->orderRepositoryContract = $orderRepositoryContract;
		$this->orderAmountRepositoryContract = $orderAmountRepositoryContract;
		$this->orderStatusRepositoryContract = $orderStatusRepositoryContract;
	}

	/**
	 *  创建订单
	 * @param array $data
	 * @param string|null $coupon
	 * @return Order
	 */
	function create (array $data, string $coupon = null): Order
	{
		return $this->orderRepositoryContract->createOrder($data, $coupon);
	}

	/**
	 * 更新订单信息
	 * @param int $orderId
	 * @param array $data
	 * @return Order
	 */
	function update (int $orderId, array $data): Order
	{
		return $this->orderRepositoryContract->updateOrder($data, $orderId);
	}

	/**
	 * 取消订单
	 * @param int $orderId
	 */
	function cancel (int $orderId)
	{
		$this->orderRepositoryContract->cancelOrder($orderId, []);
	}

	/**
	 * 删除订单
	 * @param int $orderId
	 * @return bool
	 */
	function delete (int $orderId): bool
	{
		return $this->orderRepositoryContract->deleteOrder($orderId);
	}

	/**
	 * 完成订单
	 * @param int $orderId
	 * @return Order
	 */
	function complete (int $orderId): Order
	{
		return $this->orderRepositoryContract->completeOrder($orderId);
	}

	/**
	 * 获取订单模型
	 * @param $orderId
	 * @return Order
	 */
	function getModel ($orderId): Order
	{
		return $this->orderRepositoryContract->findOrderById($orderId);
	}

	/**
	 * 获取用户订单列表
	 * @param int $contactId 用户ID
	 * @param int $page 页码
	 * @param int $itemsPerPage 每页多少条
	 * @param array $with 在订单列表中附加的关系数据
	 * @return PaginatedResult
	 */
	function getList (int $contactId, int $page = 1, int $itemsPerPage = 50, array $with = ["addresses", "events", "dates", "relation", "reference", "location", "payments", "documents", "comments"])
	{
		return $this->orderRepositoryContract->allOrdersByContact($contactId, $page, $itemsPerPage, $with);
	}

	/**
	 * 获取订单状态
	 * @param float $statusId
	 * @return array
	 */
	function getStatus (float $statusId): array
	{
		return $this->orderStatusRepositoryContract->get($statusId)->names->all();
	}

	/**
	 *  计算订单金额
	 * @param int $orderId
	 * @return OrderAmount
	 */
	function getAmount (int $orderId): OrderAmount
	{
		return $this->orderAmountRepositoryContract->getByOrderId($orderId);
	}
}
