<?php
namespace Plentymarket\Controllers\Web;

use Plenty\Plugin\Templates\Twig;
use Plentymarket\Controllers\BaseWebController;

/**
 * Class ContentController
 * @package Plentymarket\Controllers\Web
 */
class IndexController extends BaseWebController
{
	/**
	 * @param Twig $twig
	 * @return string
	 */
	public function index(Twig $twig):string
	{
		return $twig->render('Plentymarket::index.index');
	}

	/**
	 * @param Twig $twig
	 * @return string
	 */
	function about(Twig $twig):string
	{
		return $twig->render('Plentymarket::index.about');
	}

	/**
	 * 联系我们页面
	 * @param Twig $twig
	 * @return string
	 */
	function contact(Twig $twig):string
	{
		return $twig->render('Plentymarket::index.contact');
	}

	/**
	 * FAQ页面
	 * @param Twig $twig
	 * @return string
	 */
	function faq (Twig $twig): string
	{
		return $twig->render('Plentymarket::index.faq');
	}

	/**
	 * 登录或者注册页面
	 * @param Twig $twig
	 * @return string
	 */
	function login_register (): string
	{
		return $this->render('index.login-register');
	}
}
