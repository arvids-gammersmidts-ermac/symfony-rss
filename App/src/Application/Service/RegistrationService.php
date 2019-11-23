<?php

namespace App\Application\Service;

use App\Application\Command\RegisterUser;
use App\Application\Dto\Assembler;
use App\Application\Dto\UserDto;
use App\Application\Exception\UserAlreadyExists;
use App\Domain\Model\Email;
use App\Domain\Model\UserFactory;
use App\Infrastructure\UserRepository;

class RegistrationService
{
    use UserServiceTrait;

    private $userFactory;
    private $assembler;

    public function __construct(UserRepository $users, UserFactory $userFactory, Assembler $assembler)
    {
        $this->users = $users;
        $this->userFactory = $userFactory;
        $this->assembler = $assembler;
    }

    public function registerUser(RegisterUser $command): UserDto
    {
        if ($this->users->loadUserByUsername(Email::fromString($command->user->email))) {
            throw UserAlreadyExists::withEmail($command->user->email);
        }
        // TODO password util here

        $user = $this->userFactory->create($command->user); // TODO this not done

        $this->users->save($user);

        return $this->assembler->toUserDto($user);
    }
}
