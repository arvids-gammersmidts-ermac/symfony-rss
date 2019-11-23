<?php

namespace App\Domain\Model;

use App\Application\Dto\UserRegistrationDto;
use App\Domain\User\Entity\User;

interface UserFactory
{
    public function create(UserRegistrationDto $registrationDto): User;
}
