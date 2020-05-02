<?php

namespace Plentymarket\Extensions;

use Plenty\Plugin\Http\Request;
use Plentymarket\Services\ConfigService;

class TwigServiceContainer
{
	public function getRequst (): Request
	{
		return pluginApp(Request::class);
	}

	public function getConfig ()
	{
		return pluginApp(ConfigService::class);
	}
}
