<?php

namespace Plentymarket\Controllers\Api;

use Plenty\Plugin\Http\Response;
use Plentymarket\Controllers\BaseApiController;
use Plentymarket\Models\Wishlist;

/**
 * Class WishlistController
 * @package Plentymarket\Controllers\Api
 */
class WishlistController extends BaseApiController
{
	/**
	 * 添加到愿望清单
	 * @param $itemId
	 * @return Response
	 */
	function create ($itemId): Response
	{
		try {
			$wishlist = pluginApp(Wishlist::class);
			$wishlist->create($itemId);
			return $this->success([]);
		} catch (\Throwable $e) {
			return $this->exception($e);
		}
	}

	/**
	 * 从愿望清单中删除
	 * @param $itemId
	 * @return Response
	 */
	function delete ($itemId): Response
	{
		try {
			$wishlist = pluginApp(Wishlist::class);
			$wishlist->delete($itemId);
			return $this->success([]);
		} catch (\Throwable $e) {
			return $this->exception($e);
		}
	}

	/**
	 * 判断是否在愿望清单中
	 * @param $itemId
	 * @return Response
	 */
	function has ($itemId): Response
	{
		try {
			$wishlist = pluginApp(Wishlist::class);
			if ($wishlist->has($itemId)) {
				return $this->success([]);
			} else {
				return $this->error('');
			}
		} catch (\Throwable $e) {
			return $this->exception($e);
		}
	}

	/**
	 * 愿望清单中的数量
	 * @return Response
	 */
	function num (): Response
	{
		try {
			return $this->success(pluginApp(Wishlist::class)->num());
		} catch (\Throwable $e) {
			return $this->exception($e);
		}
	}
}
