<?php

namespace Plentymarket\Services;

use Plenty\Modules\Feedback\Contracts\FeedbackAverageRepositoryContract;
use Plenty\Modules\Feedback\Contracts\FeedbackCommentRepositoryContract;
use Plenty\Modules\Feedback\Contracts\FeedbackRatingRepositoryContract;
use Plenty\Modules\Feedback\Contracts\FeedbackRepositoryContract;

class FeedbackService
{
	private $feedbackRepositoryContract;
	private $feedbackAverageRepositoryContract;
	private $feedbackCommentRepositoryContract;
	private $feedbackRatingRepositoryContract;

	function __construct (FeedbackRepositoryContract $feedbackRepositoryContract, FeedbackAverageRepositoryContract $feedbackAverageRepositoryContract, FeedbackCommentRepositoryContract $feedbackCommentRepositoryContract, FeedbackRatingRepositoryContract $feedbackRatingRepositoryContract)
	{
		$this->feedbackRepositoryContract = $feedbackRepositoryContract;
		$this->feedbackAverageRepositoryContract = $feedbackAverageRepositoryContract;
		$this->feedbackCommentRepositoryContract = $feedbackCommentRepositoryContract;
		$this->feedbackRatingRepositoryContract = $feedbackRatingRepositoryContract;
	}

	function getFeedbacks ()
	{
		return $this->feedbackRepositoryContract->listFeedbacks();
	}

	function getAverage (int $feedbackRelationTargetId)
	{
		return $this->feedbackAverageRepositoryContract->getFeedbackAverage($feedbackRelationTargetId);
	}

	function getComments ()
	{
		return $this->feedbackCommentRepositoryContract->listFeedbackComments();
	}

	function getRatings ()
	{
		return $this->feedbackRatingRepositoryContract->listFeedbackRatings();
	}
}
