<?php

namespace App\Domain\Feed\Provider;

final class TheRegister extends FeedProvider
{
    const FEED_URL = 'https://theregister.co.uk/software/headlines.atom';

    protected function getFeedUrl(): string
    {
        return self::FEED_URL;
    }
}
