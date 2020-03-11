<?php

namespace Plentymarket\Services;

use Plenty\Modules\Comment\Contracts\CommentRepositoryContract;

class CommentService
{
	private $commentRepositoryContract;

	function __construct (CommentRepositoryContract $commentRepositoryContract)
	{
		$this->commentRepositoryContract = $commentRepositoryContract;
	}

	function getAll ()
	{
		return $this->commentRepositoryContract->listComments();
	}
}
