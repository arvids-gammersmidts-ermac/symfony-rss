<?php

namespace App\Domain\Feed\Topics;

interface WordExtractorInterface
{
    public function extract(string $title): array;
}
