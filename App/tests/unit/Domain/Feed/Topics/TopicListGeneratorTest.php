<?php

namespace Tests\Unit\Domain\Feed\Topics;

use App\Domain\Feed\Topics\TitleAndDescTopicExtractor;
use App\Domain\Feed\Topics\TopicListGenerator;
use App\Domain\Feed\Topics\UncommonWordCountExtractor;
use App\Domain\Model\Topic;
use FeedIo\Feed;
use FeedIo\Feed\Item as FeedItem;

class TopicListGeneratorTest extends \Codeception\Test\Unit
{
    private function createFeedItem(string $title, string $desc): FeedItem
    {
        $feed = new FeedItem();
        $feed->setTitle($title);
        $feed->setDescription($desc);
        $feed->setLink('https://test.com');

        return $feed;
    }

    private function createFeed(array $feedItems): Feed
    {
        $feed = new Feed();
        foreach ($feedItems as $feedItem){
            $feed->add($feedItem);
        }

        return $feed;
    }

    public function dataProvider()
    {
        return [
            'regular_feed' => [
                $this->createFeed(
                    [
                        $this->createFeedItem(
                            'Regular testing title from the rss feed.',
                            'Just a regular description with common words in it. Very regular.'
                        ),
                        $this->createFeedItem(
                            'Test with another title.',
                            'Short description.'
                        )
                    ]
                ),
                [
                    new Topic('regular',3),
                    new Topic('title',2,),
                    new Topic('description',2),
                    new Topic('testing',1),
                ]
            ],
            'empty_feed' => [
                $this->createFeed(
                    [
                        $this->createFeedItem(
                            '',
                            ''
                        ),
                    ]
                ),
                []
            ],
            'no_words_feed' => [
                $this->createFeed(
                    [
                        $this->createFeedItem(
                            '?.>;!)(+',
                            '%^&*($%£@_+">'
                        ),
                    ]
                ),
                []
            ],
        ];
    }

    /**
     * @test
     * @dataProvider dataProvider
     */
    public function it_extracts_uncommon_word_count(
        Feed $feed,
        array $expected
    )
    {
        $wordExtractor = new TopicListGenerator(new TitleAndDescTopicExtractor(new UncommonWordCountExtractor()));
        $result = $wordExtractor->extractTopTopics($feed, 4);

        $this->assertEquals($expected, $result);
    }
}
