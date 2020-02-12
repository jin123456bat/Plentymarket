<?php

namespace Plentymarket\Middlewares;

use Plenty\Plugin\Http\Request;
use Plenty\Plugin\Http\Response;
use Plenty\Plugin\Middleware;

/**
 * Class Auth
 * @package Plentymarket\Middlewares
 */
class Auth extends Middleware
{

	/**
	 * @param Request $request
	 */
	public function before (Request $request)
	{
		// TODO: Implement before() method.
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
