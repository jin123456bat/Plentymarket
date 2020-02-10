<?php
namespace Plentymarket\Controllers\Web;

use Plenty\Plugin\Controller;
use Plenty\Plugin\Templates\Twig;

class AccountController extends Controller
{
	function index(Twig $twig):string
	{
		return $twig->render('Plentymarket::account.index');
	}

	function cart(Twig $twig):string
	{
		return $twig->render('Plentymarket::account.cart');
	}

	function checkout(Twig $twig):string
	{
		return $twig->render('Plentymarket::account.checkout');
	}

	function wishlist(Twig $twig):string
	{
		return $twig->render('Plentymarket::account.wishlist');
	}

	function login()
	{

	}

	function register()
	{

	}
}
