<?php

namespace App\Domain\Model;

use App\Application\Dto\UserRegistrationDto;
use App\Domain\User\Entity\User;

interface UserFactoryInterface
{
    public function create(UserRegistrationDto $registrationDto): User;
}
