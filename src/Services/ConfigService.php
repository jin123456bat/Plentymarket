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
	private $webstoreConfig;

	public function get($key)
	{
		if( $this->webstoreConfig === null )
		{
			$webstoreConfig = pluginApp(WebstoreConfigurationRepositoryContract::class);
			$this->webstoreConfig = $webstoreConfig->findByWebstoreId(Utils::getWebstoreId());
		}

		return $this->webstoreConfig->$key;
	}
}
