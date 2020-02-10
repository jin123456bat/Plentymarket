<?php
namespace Plentymarket\Controllers\Api;

use Plentymarket\Controllers\BaseApiController;

/**
 * Class ContentController
 * @package HelloWorld\Controllers
 */
class IndexController extends BaseApiController
{
	/**
	 *
	 */
	public function login()
	{
		return $this->success('登录成功');
	}

	public function register()
	{
		return $this->error('注册失败');
	}
}
