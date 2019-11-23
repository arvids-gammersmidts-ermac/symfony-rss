<?php

namespace App\Application\Exception;

use RuntimeException;

class UserNotFound extends RuntimeException
{
    public static function byId(string $id): self
    {
        return new self(sprintf('User by id "%s" not found.', $id));
    }

    public static function byEmail(string $email): self
    {
        return new self(sprintf('User by email "%s" not found.', $email));
    }
}
