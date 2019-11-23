<?php

namespace App\Application\Service;

use App\Application\Command\FetchFeed;
use App\Domain\Model\FeedProviderInterface;
use FeedIo\FeedInterface;

class FeedService
{
    use UserServiceTrait;

    private $feedProvider;

    public function __construct(FeedProviderInterface $feedProvider)
    {
        $this->feedProvider = $feedProvider;
    }

    public function getFeed(FetchFeed $command): FeedInterface // TODO return dto with feed and sorted counts
    {
        // TODO depending on command call cached or new feed
        $feed = $this->feedProvider->obtain();

        return $feed;
    }
}
