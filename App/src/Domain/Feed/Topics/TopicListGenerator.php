<?php

namespace App\Domain\Feed\Topics;

use App\Domain\Model\Topic;
use FeedIo\Feed\Item as FeedItem;
use FeedIo\FeedInterface;

class TopicListGenerator
{
    private $topicExtractor;

    public function __construct(TopicExtractorInterface $topicExtractor)
    {
        $this->topicExtractor = $topicExtractor;
    }

    public function extractTopTopics(FeedInterface $feed, int $count): array
    {
        $result = [];
        $topicCounts = [];
        /** @var FeedItem $feedItem */
        foreach ($feed as $feedItem){
            $topicCountFromFeedNode = $this->topicExtractor->extractTopicCountFromFeedItem($feedItem);
            $topicCounts = $this->sumArrays($topicCounts, $topicCountFromFeedNode);
        }

        $topTopicCounts = $this->getTopTopics($topicCounts, $count);

        foreach ($topTopicCounts as $key => $value){
            $result[] = new Topic($key, $value);
        }

        return $result;
    }

    private function getTopTopics(array $allTopics, int $count): array
    {
        arsort($allTopics);

        return array_slice($allTopics, 0,$count);
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
