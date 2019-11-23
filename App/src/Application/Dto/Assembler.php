<?php

namespace App\Application\Dto;

use App\Domain\User\Entity\User;

class Assembler
{
    public function __construct()
    {
    }

    public function toUserDto(User $user): UserDto
    {
        $dto = new UserDto();

        $dto->id = (string) $user->getId();
        $dto->email = (string) $user->getEmail();
        $dto->name = (string) $user->getUserInfo()->getName();

        return $dto;
    }
}
