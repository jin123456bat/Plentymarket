<?php

namespace Plentymarket\Migrations;

use Plenty\Modules\Plugin\DataBase\Contracts\Migrate;
use Plentymarket\Models\Wishlist;

class CreateWishlistTable
{
	public function run (Migrate $migrate)
	{
		$migrate->createTable(Wishlist::class);
	}
}
