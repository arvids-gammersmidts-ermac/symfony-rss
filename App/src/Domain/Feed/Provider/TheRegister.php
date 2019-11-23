<?php

namespace App\Domain\Feed\Provider;

use App\Domain\Model\FeedProvider;

final class TheRegister extends FeedProvider
{
    const FEED_URL = 'https://theregister.co.uk/software/headlines.atom'; // TODO maybe to config object

    protected function getFeedUrl(): string
    {
        return self::FEED_URL;
    }
}
