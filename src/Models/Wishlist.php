<?php

namespace Plentymarket\Models;

use Plenty\Modules\Plugin\DataBase\Contracts\Model;
use Plentymarket\Services\AccountService;
use Plentymarket\Services\SessionService;

/**
 * Class Wishlist
 * @package Plentymarket\Models
 *
 * @property int $id
 * @property int $contactId
 * @property int $itemId
 * @Relation(model="Plenty\Modules\Account\Contact\Models\Contact", name="wishlist_contact_id_fk", attribute="id", column="contactId", onUpdate="Cascade", onDelete="Cascade")
 */
class Wishlist extends Model
{
	/**
	 * 主键ID
	 * @var int
	 */
	public $id = 0;

	/**
	 * 联系人ID
	 * @var int
	 */
	public $contactId = 0;

	/**
	 * 商品ID
	 * @var int
	 */
	public $itemId = 0;

	/**
	 * 表名
	 * @return string
	 */
	public function getTableName (): string
	{
		return 'Plentymarket::wishlist';
	}

	/**
	 * 获取商品ID列表
	 * @return array
	 */
	public function getContactItemId ()
	{
		$accountService = pluginApp(AccountService::class);
		$contactId = $accountService->getContactId();
		if (!empty($contactId)) {
			$session = pluginApp(SessionService::class);
			$wishlist = $session->get('wishlist');
			if (empty($wishlist)) {
				return [];
			}

			$result = [];
			$wishlist = json_decode($wishlist, true);
			foreach ($wishlist as $value) {
				if ($value['contactId'] == $contactId) {
					$result[] = $value['itemId'];
				}
			}

			return $result;
		}
		return [];
	}

	/**
	 * 愿望清单中的商品数量
	 * @return int
	 */
	public function num ()
	{
		$accountService = pluginApp(AccountService::class);
		$contactId = $accountService->getContactId();
		if (!empty($contactId)) {
//			$database = pluginApp(DataBase::class);
//			$wishlist = $database->query(Wishlist::class)->where('contactId', '=', $contactId)->get();
//			return count($wishlist);

			$session = pluginApp(SessionService::class);
			$wishlist = $session->get('wishlist');
			if (empty($wishlist)) {
				return 0;
			}

			$wishlist = json_decode($wishlist, true);
			return count($wishlist);
		}
		return 0;
	}

	/**
	 * 添加商品到愿望清单
	 * @param $itemId
	 * @return bool
	 */
	public function create ($itemId)
	{
		$accountService = pluginApp(AccountService::class);
		$contactId = $accountService->getContactId();
		if (!empty($contactId) && !$this->has($itemId)) {
//			$database = pluginApp(DataBase::class);
//			$wishlist = pluginApp(Wishlist::class);
//			$wishlist->contactId = $contactId;
//			$wishlist->itemId = $itemId;
//			return $database->save($wishlist);

			$session = pluginApp(SessionService::class);
			$wishlist = $session->get('wishlist');
			if (empty($wishlist)) {
				$wishlist = [];
			}

			$wishlist[] = [
				'contactId' => $contactId,
				'itemId' => $itemId,
			];

			$session->set('wishlist', json_encode($wishlist));
			return true;
		}
		return false;
	}

	/**
	 * 商品是否已经添加到愿望清单
	 * @param $itemId
	 * @return bool 已经添加返回true  否则返回false
	 */
	public function has ($itemId)
	{
		$accountService = pluginApp(AccountService::class);
		$contactId = $accountService->getContactId();
		if (!empty($contactId)) {
//			$database = pluginApp(DataBase::class);
//			$wishlist = $database->query(Wishlist::class)->where('contactId', '=', $contactId)->where('itemId', '=', $itemId)->get();
//			if (empty($wishlist)) {
//				return false;
//			}
//			return true;

			$session = pluginApp(SessionService::class);
			$wishlist = $session->get('wishlist');
			if (empty($wishlist)) {
				return false;
			}

			$wishlist = json_decode($wishlist, true);
			foreach ($wishlist as $list) {
				if ($list['contactId'] == $contactId && $list['itemId'] == $itemId) {
					return true;
				}
			}
			return false;
		}
		return false;
	}

	/**
	 * 从愿望清单中删除商品
	 * @param $itemId
	 * @return bool 成功返回true  失败返回false
	 */
	public function delete ($itemId)
	{
		$accountService = pluginApp(AccountService::class);
		$contactId = $accountService->getContactId();
		if (!empty($contactId) && $this->has($itemId)) {
//			$database = pluginApp(DataBase::class);
//			$wishlist = $database->query(Wishlist::class)->where('contactId', '=', $contactId)->where('itemId', '=', $itemId)->get();
//			return $database->delete($wishlist[0]);

			$session = pluginApp(SessionService::class);
			$wishlist = $session->get('wishlist');
			if (empty($wishlist)) {
				return false;
			}

			$wishlist = json_decode($wishlist, true);
			foreach ($wishlist as $key => $value) {
				if ($value['contactId'] == $contactId && $value['itemId'] == $itemId) {
					unset($wishlist[$key]);
				}
			}

			$session->set('wishlist', json_encode($wishlist));

			return true;
		}
		return false;
	}
}
