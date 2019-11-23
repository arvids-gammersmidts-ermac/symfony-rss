<?php

namespace App\Application\Service;

use App\Application\Exception\UserNotFound;
use App\Domain\Model\Email;
use App\Domain\User\Entity\User;
use App\Infrastructure\UserRepository;

trait UserServiceTrait // TODO use this with email check service
{
    /**
     * @var UserRepository
     */
    private $users;

    /**
     * @throws UserNotFound
     */
    private function getUser(string $id): User
    {
        if (filter_var($id, FILTER_VALIDATE_EMAIL)) {
            if (null === $user = $this->users->loadUserByUsername(Email::fromString($id))) {
                throw UserNotFound::byEmail($id);
            }
        }
        // TODO improve

        return $user;
    }
}
