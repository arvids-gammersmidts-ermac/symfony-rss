<?php

namespace App\Domain\Model;

use FeedIo\FeedInterface;

interface FeedProviderInterface
{
    public function obtain(): FeedInterface;
}
