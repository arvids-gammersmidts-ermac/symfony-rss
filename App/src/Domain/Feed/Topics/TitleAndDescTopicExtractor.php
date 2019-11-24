<?php

namespace App\Domain\Feed\Topics;

use FeedIo\Feed\Item as FeedItem;

class TitleAndDescTopicExtractor implements TopicExtractorInterface
{
    private $wordCountExtractor;

    public function __construct(UncommonWordCountExtractor $uncommonWordCountExtractor)
    {
        $this->wordCountExtractor = $uncommonWordCountExtractor;
    }

    public function extractTopicCountFromFeedItem(FeedItem $node): array
    {
        $titleTopics = $this->wordCountExtractor->extract($node->getTitle());
        $descriptionTopics = $this->wordCountExtractor->extract($node->getDescription());

        return $this->sumArrays($titleTopics, $descriptionTopics);
    }

    private function sumArrays(array $a1, array $a2): array
    {
        $sum = array();
        foreach (array_keys($a1 + $a2) as $key) {
            $sum[(string)$key] = (int)($a1[$key] ?? 0) + (int)($a2[$key] ?? 0);
        }

        return $sum;
    }
}
