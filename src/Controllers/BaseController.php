<?php

namespace Plentymarket\Controllers;

use Plenty\Plugin\Controller;
use Plenty\Plugin\Translation\Translator;

class BaseController extends Controller
{
	/**
	 * @var Translator
	 */
	private $translator;

	function __construct ()
	{
		$this->translator = pluginApp(Translator::class);
	}

	/**
	 * 翻译
	 * @param $key
	 * @return string
	 */
	protected function trans ($key): string
	{
		return $this->translator->trans('Plentymarket::' . $key);
	}
}
