<?php

namespace App\Application\Exception;

use RuntimeException;

class InvalidPassword extends RuntimeException
{
    public static function doesNotCorrespondWithPattern(): self
    {
        return new self('Your password must contain at least one number, one uppercase letter, one lowercase letter, and at least 8 or more characters');
    }
}
