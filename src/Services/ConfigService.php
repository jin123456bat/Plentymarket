<?php

namespace Plentymarket\Services;

use Plenty\Modules\System\Contracts\WebstoreConfigurationRepositoryContract;
use Plenty\Modules\System\Models\WebstoreConfiguration;
use Plenty\Plugin\Application;

class ConfigService
{

	/**
	 * Get the activate languages of the webstore
	 */
    public function getLanguageList()
	{
        $activeLanguages = [];

        $templateConfigService = pluginApp(TemplateConfigService::class);
        $languages = $templateConfigService->get('language.active_languages');

        if(!is_null($languages) && strlen($languages))
        {
            $activeLanguages = explode(', ', $languages);
        }

        if(!in_array($this->getWebstoreConfig()->defaultLanguage, $activeLanguages))
        {
            $activeLanguages[] = $this->getWebstoreConfig()->defaultLanguage;
        }

		return $activeLanguages;
	}
}
