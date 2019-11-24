<?php

namespace App\Application\Dto;

use App\Domain\Model\Topic;
use FeedIo\FeedInterface;

class FeedDto
{
    /**
     * @var FeedInterface
     */
    public $feed;

    /**
     * @var Topic[]
     */
    public $topics;
}
