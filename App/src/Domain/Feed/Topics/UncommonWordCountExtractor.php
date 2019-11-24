<?php

namespace App\Domain\Feed\Topics;

final class UncommonWordCountExtractor extends WordCountExtractor
{
    public function __construct()
    {
        parent::__construct();
    }

    public function extract(string $title): array
    {
        $words = parent::extract($title);

        return $this->removeCommonWords($words);
    }

    private function removeCommonWords(array $words)
    {
        return array_diff_key($words, array_flip($this->getCommonWords()));
    }

    private function getCommonWords(): array
    {
        return [
            'the',
            'be',
            'to',
            'of',
            'and',
            'a',
            'in',
            'that',
            'have',
            'i',
            'it',
            'for',
            'not',
            'on',
            'with',
            'he',
            'as',
            'you',
            'do',
            'at',
            'this',
            'but',
            'his',
            'by',
            'from',
            'they',
            'we',
            'say',
            'her',
            'she',
            'or',
            'will',
            'an',
            'my',
            'one',
            'all',
            'would',
            'there',
            'their',
            'what',
            'so',
            'up',
            'out',
            'if',
            'about',
            'who',
            'get',
            'which',
            'go',
            'when',
        ];
    }
}
