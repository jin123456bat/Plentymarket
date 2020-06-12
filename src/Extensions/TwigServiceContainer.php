<?php

namespace Plentymarket\Extensions;

use Plenty\Plugin\Http\Request;
use Plentymarket\Services\ConfigService;
use Plentymarket\Services\TranslateService;

class TwigServiceContainer
{
	public function getRequst (): Request
	{
		return pluginApp(Request::class);
	}

	public function getConfig (): ConfigService
	{
		return pluginApp(ConfigService::class);
	}

	public function getTranslate (): TranslateService
	{
		return pluginApp(TranslateService::class);
	}
}
