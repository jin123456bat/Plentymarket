<?php

namespace Plentymarket\Middlewares;

use Plenty\Plugin\Http\Request;
use Plenty\Plugin\Http\Response;
use Plenty\Plugin\Middleware;
use Plentymarket\Guards\AuthGuard;

/**
 * Class Auth
 * @package Plentymarket\Middlewares
 */
class AuthMiddleware extends Middleware
{

	/**
	 * @param Request $request
	 */
	public function before (Request $request)
	{
		AuthGuard::assertOrRedirect(true, '/index/login_register');
	}

	/**
	 * @param Request $request
	 * @param Response $response
	 * @return Response
	 */
	public function after (Request $request, Response $response): Response
	{
		// TODO: Implement after() method.
		return $response;
	}
}
