<?php

namespace App\Domain\Feed\Topics;

class WordCountExtractor implements WordExtractorInterface
{
    public function __construct()
    {
    }

    public function extract(string $text): array
    {
        return array_count_values(array_map('strtolower', str_word_count($text, 1)));
    }
}
