<?php

namespace App\Domain\Feed\Provider;

use FeedIo\FeedInterface;

interface FeedProviderInterface
{
    public function obtain(): FeedInterface;
}
