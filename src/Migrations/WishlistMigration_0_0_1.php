<?php

namespace Plentymarket\Migrations;

use Plenty\Modules\Plugin\DataBase\Contracts\Migrate;
use Plentymarket\Models\Wishlist;

class WishlistMigration_0_0_1
{
	public function run (Migrate $migrate)
	{
		$migrate->createTable(Wishlist::class);
	}
}
