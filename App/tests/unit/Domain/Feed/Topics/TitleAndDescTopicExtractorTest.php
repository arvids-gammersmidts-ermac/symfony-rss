<?php

namespace Tests\Unit\Domain\Feed\Topics;

use App\Domain\Feed\Topics\TitleAndDescTopicExtractor;
use App\Domain\Feed\Topics\UncommonWordCountExtractor;
use FeedIo\Feed\Item as FeedItem;

class TitleAndDescTopicExtractorTest extends \Codeception\Test\Unit
{
    private function createFeedItem(string $title, string $desc): FeedItem
    {
        $feed = new FeedItem();
        $feed->setTitle($title);
        $feed->setDescription($desc);
        $feed->setLink('https://test.com');

        return $feed;
    }

    public function dataProvider()
    {
        return [
            'regular_feed' => [
                $this->createFeedItem(
                    'Regular testing title from the rss feed.',
                    'Just a regular description with common words in it. Very regular.'
                ),
                [
                    'regular' => 3,
                    'testing' => 1,
                    'title' => 1,
                    'rss' => 1,
                    'feed' => 1,
                    'just' => 1,
                    'description' => 1,
                    'common' => 1,
                    'words' => 1,
                    'very' => 1,
                ]
            ],
            'empty_feed' => [
                $this->createFeedItem(
                    '',
                    ''
                ),
                []
            ],
            'no_words_feed' => [
                $this->createFeedItem(
                    '?.>;!)(+',
                    '%^&*($%£@_+">'
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
        FeedItem $feed,
        array $expected
    )
    {
        $wordExtractor = new TitleAndDescTopicExtractor(new UncommonWordCountExtractor());
        $result = $wordExtractor->extractTopicCountFromFeedItem($feed);

        $this->assertEquals($expected, $result);
    }
}
