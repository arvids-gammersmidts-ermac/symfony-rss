<?php

namespace App\Utils;

/**
 * Class Validation
 * @package App\Utils
 */
class PasswordUtil // TODO move to registration service
{
    /**
     * @param $plainTextPassword
     * @return bool
     */
    public function isPasswordPatternValid($plainTextPassword)
    {
        $passwordPattern = '/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/';
        if(!preg_match($passwordPattern,$plainTextPassword)) {
            return false;
        }
        return true;
    }
}
