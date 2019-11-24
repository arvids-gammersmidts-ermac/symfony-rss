<?php

namespace App\Domain\Feed\Topics;

class WordCountExtractor implements WordExtractorInterface
{
    const MINIMUM_WORD_LENGTH = 2;

    public function __construct()
    {
    }

    public function extract(string $text): array
    {
        $words = array_count_values(array_map('strtolower', str_word_count($text, 1)));

        return $this->removeShortWords($words);
    }

    private function removeShortWords($words): array
    {
        return array_filter($words, function($word) {
            return strlen($word) > self::MINIMUM_WORD_LENGTH;
        }, ARRAY_FILTER_USE_KEY);
    }
}
