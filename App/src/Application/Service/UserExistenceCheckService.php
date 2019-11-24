<?php

namespace App\Application\Service;

use App\Infrastructure\UserRepository;

class UserExistenceCheckService
{
    use UserServiceTrait;

    private $userFactory;
    private $assembler;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function exists(string $email): bool
    {
        return !empty($this->users->findOneByUsername($email));
    }
}
