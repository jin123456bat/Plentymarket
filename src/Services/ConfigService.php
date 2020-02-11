<?php
namespace Plentymarket\Services;

use Plenty\Modules\System\Contracts\WebstoreConfigurationRepositoryContract;
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
			$this->webstoreConfig = pluginApp(WebstoreConfigurationRepositoryContract::class)->findByWebstoreId(Utils::getWebstoreId());
		}

		return $this->webstoreConfig->toArray()[$key]??null;
	}
}
