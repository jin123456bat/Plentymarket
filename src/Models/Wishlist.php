<?php

namespace Plentymarket\Models;

use Plenty\Modules\Plugin\DataBase\Contracts\DataBase;
use Plenty\Modules\Plugin\DataBase\Contracts\Model;
use Plentymarket\Services\AccountService;

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
	 * 愿望清单中的商品数量
	 * @return int
	 */
	public function num ()
	{
		$accountService = pluginApp(AccountService::class);
		$contactId = $accountService->getContactId();
		if (!empty($contactId)) {
			$database = pluginApp(DataBase::class);
			$wishlist = $database->query(Wishlist::class)->where('contactId', '=', $contactId)->get();
			return count($wishlist);
		}
		return 0;
	}

	/**
	 * 添加商品到愿望清单
	 * @param $itemId
	 * @return Wishlist|null 成功返回Wishlist 失败返回null
	 */
	public function create ($itemId)
	{
		$accountService = pluginApp(AccountService::class);
		$contactId = $accountService->getContactId();
		if (!empty($contactId) && !$this->has($itemId)) {
			$database = pluginApp(DataBase::class);
			$wishlist = pluginApp(Wishlist::class);
			$wishlist->contactId = $contactId;
			$wishlist->itemId = $itemId;
			return $database->save($wishlist);
		}
		return null;
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
			$database = pluginApp(DataBase::class);
			$wishlist = $database->query(Wishlist::class)->where('contactId', '=', $contactId)->where('itemId', '=', $itemId)->get();
			if (empty($wishlist)) {
				return false;
			}
			return true;
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
			$database = pluginApp(DataBase::class);
			$wishlist = $database->query(Wishlist::class)->where('contactId', '=', $contactId)->where('itemId', '=', $itemId)->get();
			return $database->delete($wishlist[0]);
		}
		return false;
	}
}
