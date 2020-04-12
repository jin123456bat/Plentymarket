<?php

namespace Plentymarket\Services;

use Plenty\Modules\Order\Contracts\OrderAmountRepositoryContract;
use Plenty\Modules\Order\Contracts\OrderRepositoryContract;
use Plenty\Modules\Order\Models\Order;
use Plenty\Modules\Order\Models\OrderAmount;
use Plenty\Modules\Order\Status\Contracts\OrderStatusRepositoryContract;

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

	function setStatus ()
	{
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
	 * @param int $page 页码
	 * @param int $itemsPerPage 每页多少条
	 * @param array $with 在订单列表中附加的关系数据
	 * @return array
	 */
	function getList (int $page = 1, int $itemsPerPage = 50, array $with = ["addresses", "events", "dates", "relation", "reference", "location", "payments", "documents", "comments"])
	{
		$contactId = pluginApp(AccountService::class)->getContactId();
		if (!empty($contactId)) {
			return $this->orderRepositoryContract->allOrdersByContact($contactId, $page, $itemsPerPage, $with)->toArray();
		}

		return [
			'page' => $page,
			'totalsCount' => 0,
			'isLastPage' => true,
			'entries' => [],
			'lastPageNumber' => 1,
			'firstOnPage' => 1,
			'lastOnPage' => 1,
			'itemsPerPage' => $itemsPerPage
		];
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
