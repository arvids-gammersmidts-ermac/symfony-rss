<?php

namespace App\Domain\Model;

final class Email
{
    private $email;

    public static function fromString(string $email): self
    {
        return new self($email);
    }

    public function email(): string
    {
        return $this->email;
    }

    public function __toString(): string
    {
        return $this->email;
    }

    public function sameAs(self $email): bool
    {
        return $this->email === $email->email;
    }

    private function __construct(string $email)
    {
        $this->email = strtolower($email);
    }
}
