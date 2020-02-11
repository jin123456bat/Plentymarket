<?php
namespace Plentymarket\Controllers\Web;

use Plenty\Plugin\Templates\Twig;
use Plentymarket\Controllers\BaseWebController;

/**
 * Class AccountController
 * @package Plentymarket\Controllers\Web
 */
class AccountController extends BaseWebController
{
	/**
	 * @param Twig $twig
	 * @return string
	 */
	function index(Twig $twig):string
	{
		return $twig->render('Plentymarket::account.index');
	}

	/**
	 * @param Twig $twig
	 * @return string
	 */
	function cart(Twig $twig):string
	{
		return $twig->render('Plentymarket::account.cart');
	}

	/**
	 * @param Twig $twig
	 * @return string
	 */
	function checkout(Twig $twig):string
	{
		return $twig->render('Plentymarket::account.checkout');
	}

	/**
	 * @param Twig $twig
	 * @return string
	 */
	function wishlist(Twig $twig):string
	{
		return $twig->render('Plentymarket::account.wishlist');
	}
}
