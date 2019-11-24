<?php

namespace App\Domain\Feed\Provider;

use FeedIo\FeedInterface;
use FeedIo\FeedIo;

abstract class FeedProvider implements FeedProviderInterface
{
    /**
     * @type \FeedIo\FeedIo
     */
    private $feedIo;

    public function __construct(FeedIo $feedIo)
    {
        $this->feedIo = $feedIo;
    }

    public function obtain(): FeedInterface
    {
        return $this->feedIo->read($this->getFeedUrl())->getFeed();
    }

    abstract protected function getFeedUrl(): string;
}
