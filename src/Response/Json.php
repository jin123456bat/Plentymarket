<?php
namespace Plentymarket\Response;

use Symfony\Component\HttpFoundation\Response;

class Json extends Response
{
	function __construct ($content = [])
	{
		parent::__construct(json_encode($content), 200, []);
	}
}
