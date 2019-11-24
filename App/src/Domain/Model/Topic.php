<?php

namespace App\Domain\Model;

final class Topic
{
    private $name;

    private $count;

    public function name(): string
    {
        return $this->name;
    }

    public function count(): int
    {
        return $this->count;
    }

    public function __construct(string $name, int $count)
    {
        $this->name = strtolower($name);
        $this->count = $count;
    }
}
