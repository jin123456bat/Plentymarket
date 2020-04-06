<?php

namespace Plentymarket\Models;

use Plenty\Modules\Plugin\DataBase\Contracts\Model;

/**
 * Class PayPalAccessToken
 *
 * @property int $id;
 * @property string $scope;
 * @property string $access_token;
 * @property string $token_type;
 * @property string $app_id;
 * @property int $expires_in;
 * @property string $nonce;
 * @property datetime $created_at;
 *
 */
class PayPalAccessToken extends Model
{
	public $id = 0;
	public $scope;
	public $access_token;
	public $token_type;
	public $app_id;
	public $expires_in;
	public $nonce;

	public $created_at;

	public function getTableName (): string
	{
		return 'pay_pal_access_token';
	}
}
