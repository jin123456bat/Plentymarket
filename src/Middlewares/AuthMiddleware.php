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
	 * @throws \Exception
	 */
	public function before (Request $request)
	{
		try {
			$path = $request->getRequestUri();
			$path_first = current(array_filter(explode('/', $path)));
			if (in_array($path_first, ['account'])) {
				pluginApp(AuthGuard::class)->assertOrRedirect(true, '/index/login_register');
			}
		} catch (\Throwable $e) {
			throw new \Exception('中间件判断登录异常:' . $e->getMessage() . 'File:' . $e->getFile() . 'Line:' . $e->getLine());
		}
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
