<?php

namespace App\Domain\Feed\Topics;

use FeedIo\Feed\Item as FeedItem;

interface TopicExtractorInterface
{
    public function extractTopicCountFromFeedItem(FeedItem $node): array;
}
