<?php
namespace Plentymarket\Controllers\Api;

use Illuminate\Http\Request;
use Plenty\Plugin\Controller;
use Plentymarket\Response\Json;

/**
 * Class ContentController
 * @package HelloWorld\Controllers
 */
class IndexController extends Controller
{
	/**
	 *
	 */
	public function login(Request $request)
	{
		return new Json([
			'code' => 1,
			'message'=>'OK',
		]);
	}

	public function register()
	{
		return new Json([
			'code' => 1,
			'message'=>'OK',
		]);
	}
}
