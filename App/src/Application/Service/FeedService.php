<?php

namespace App\Application\Service;

use App\Application\Command\FetchFeed;
use App\Application\Dto\FeedDto;
use App\Domain\Feed\Provider\FeedProviderInterface;
use App\Domain\Feed\Topics\TopicListGenerator;

class FeedService
{
    const TOPIC_COUNT = 10;

    private $feedProvider;
    private $topicListGenerator;

    public function __construct(FeedProviderInterface $feedProvider, TopicListGenerator $topicListGenerator)
    {
        $this->feedProvider = $feedProvider;
        $this->topicListGenerator = $topicListGenerator;
    }

    public function getFeed(FetchFeed $command): FeedDto
    {
        // TODO depending on command call cached or new feed
        $feed = $this->feedProvider->obtain();
        $topTopics = $this->topicListGenerator->extractTopTopics($feed, $command->topicCount);

        $feedDto = new FeedDto();
        $feedDto->feed = $feed;
        $feedDto->topics = $topTopics;

        return $feedDto;
    }
}
