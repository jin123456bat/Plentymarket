<?php

namespace Plentymarket\Services;

use Plenty\Modules\System\Contracts\WebstoreConfigurationRepositoryContract;
use Plenty\Plugin\ConfigRepository;
use Plentymarket\Helper\Utils;

/**
 * Class ConfigService
 * @package Plentymarket\Services
 */
class ConfigService
{
	/*
	 * @var WebstoreConfiguration
	 */
	private $webstoreConfiguration;

	/**
	 * @var ConfigRepository
	 */
	private $configRepository;

	function __construct (ConfigRepository $configRepository)
	{
		$this->configRepository = $configRepository;
	}

	/**
	 * 获取网站配置
	 * @param $key
	 * @return |null
	 */
	public function getWebsiteConfig ($key)
	{
		if ($this->webstoreConfiguration === null) {
			$this->webstoreConfiguration = pluginApp(WebstoreConfigurationRepositoryContract::class)->findByWebstoreId(Utils::getWebstoreId());
		}

		return $this->webstoreConfiguration->toArray()[$key] ?? null;
	}

	/**
	 * 获取模板配置
	 * @param string $key
	 * @param null $default
	 * @return mixed
	 */
	public function getTemplateConfig (string $key, $default = null)
	{
		return $this->configRepository->get('Plentymarket.' . $key, $default);
	}

	/**
	 * 获取模板配置
	 * @param string $key
	 * @param bool $default
	 * @return bool
	 */
	public function getTemplateConfigBool ($key, $default = false)
	{
		$value = $this->getTemplateConfig($key, $default);
		if ($value === "true" || $value === "false" || $value === "1" || $value === "0" || $value === 1 || $value === 0) {
			return $value === "true" || $value === "1" || $value === 1;
		}
		return $default;
	}

	/**
	 * 获取语言列表
	 */
	public function getActiveLanguageList ()
	{
		$activeLanguages = [];

		$languages = $this->getTemplateConfig('language.active_languages');
		if (!is_null($languages) && strlen($languages)) {
			$activeLanguages = explode(', ', $languages);
		}

		if (!in_array($this->getWebsiteConfig('defaultLanguage'), $activeLanguages)) {
			$activeLanguages[] = $this->getWebsiteConfig('defaultLanguage');
		}

		return $activeLanguages;
	}
}
