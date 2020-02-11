<?php
namespace Plentymarket\Services;

use Plenty\Modules\System\Contracts\WebstoreConfigurationRepositoryContract;
use Plenty\Modules\System\Models\WebstoreConfiguration;
use Plenty\Plugin\Application;
use Plentymarket\Helper\Utils;

class ConfigService
{
	/*
	 * @var WebstoreConfiguration
	 */
	private $webstoreConfiguration;

	public function get($key)
	{
		if( $this->webstoreConfiguration === null )
		{
			$webstoreConfig = pluginApp(WebstoreConfigurationRepositoryContract::class);
			$this->webstoreConfiguration = $webstoreConfig->findByWebstoreId(Utils::getWebstoreId());
		}

		return $this->webstoreConfiguration->$key;
	}
}
