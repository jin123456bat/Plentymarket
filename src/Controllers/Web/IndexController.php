<?php
namespace Plentymarket\Controllers\Web;

use Plenty\Plugin\Controller;
use Plenty\Plugin\Templates\Twig;

/**
 * Class ContentController
 * @package HelloWorld\Controllers
 */
class IndexController extends Controller
{
	/**
	 * @param Twig $twig
	 * @return string
	 */
	public function index(Twig $twig):string
	{
		return $twig->render('Plentymarket::index.index');
	}

	function about(Twig $twig):string
	{
		return $twig->render('Plentymarket::index.about');
	}

	function contact(Twig $twig):string
	{
		return $twig->render('Plentymarket::index.contact');
	}

	function faq(Twig $twig):string
	{
		return $twig->render('Plentymarket::index.faq');
	}

	function login_register(Twig $twig):string
	{
		return $twig->render('Plentymarket::index.login-register');
	}
}
