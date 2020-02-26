<?php

namespace Plentymarket\Guards;

use Plenty\Modules\Frontend\Services\AccountService;

class AuthGuard extends AbstractGuard
{
	/**
	 * @return bool
	 */
	protected function assert ()
	{
		return pluginApp(AccountService::class)->getIsAccountLoggedIn();
	}
}
