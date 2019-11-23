<?php

namespace App\Application\Exception;

use RuntimeException;

class UserAlreadyExists extends RuntimeException
{
    public static function withEmail(string $email): self
    {
        return new self(sprintf('User with email "%s" already exists.', $email));
    }
}
