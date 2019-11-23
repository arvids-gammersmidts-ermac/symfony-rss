<?php

namespace App\Domain\Model;

use App\Application\Dto\UserRegistrationDto;
use App\Domain\User\Entity\User;
use App\Domain\User\Entity\UserInfo;
use App\Infrastructure\UserRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ConfigurableUserFactory implements UserFactory
{
    private $users;
    private $passwordEncoder;

    public function __construct(UserRepository $users, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->users = $users;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function create(UserRegistrationDto $registrationDto, User $creator = null): User
    {
        // TODO improve add assertion

        $user = new User();
        $user->setUsername(Email::fromString($registrationDto->email));
        $user->setPlainPassword($registrationDto->password);
        $user->setPassword(
            $this->passwordEncoder->encodePassword($user, $user->getPlainPassword())
        );

        $userInfo = new UserInfo();
        $userInfo->setUser($user);
        $userInfo->setName($registrationDto->name);

        $user->setUserInfo($userInfo);

        // TODO CC to separate functions

        return $user;
    }
}
