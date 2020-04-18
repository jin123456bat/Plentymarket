<?php

namespace Plentymarket\Extensions;

use Plenty\Plugin\Http\Request;

class TwigServiceContainer
{
	public function getRequst (): Request
	{
		return pluginApp(Request::class);
	}
}
