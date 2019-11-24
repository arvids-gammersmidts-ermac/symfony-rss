<?php

namespace Tests\Unit\Domain\Feed\Topics;

use App\Domain\Feed\Topics\WordCountExtractor;

class WordCountExtractorTest extends \Codeception\Test\Unit
{
    public function dataProvider()
    {
        return [
            'regular_sentence' => [
                'Regular testing title from rss feed.',
                [
                    'regular' => 1,
                    'testing' => 1,
                    'title' => 1,
                    'from' => 1,
                    'rss' => 1,
                    'feed' => 1,
                ]
            ],
            'some_words_repeat' => [
                'Regular testing title or is it a testing title?',
                [
                    'regular' => 1,
                    'testing' => 2,
                    'title' => 2,
                    'or' => 1,
                    'is' => 1,
                    'it' => 1,
                    'a' => 1,
                ]
            ],
            'same_word_on_repeat' => [
                'Same SAME sAME SAme',
                [
                    'same' => 4,
                ]
            ],
            'same_word_splitting' => [
                'Same.SAME?sAME_SAme',
                [
                    'same' => 4,
                ]
            ],
            'empty_text' => [
                '',
                []
            ],
        ];
    }

    /**
     * @test
     * @dataProvider dataProvider
     */
    public function it_extracts_word_count(
        string $text,
        array $expected
    )
    {
        $wordExtractor = new WordCountExtractor();
        $result = $wordExtractor->extract($text);

        $this->assertEquals($expected, $result);
    }
}
