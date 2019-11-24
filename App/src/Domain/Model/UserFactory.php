<?php

namespace App\Domain\Model;

use App\Application\Dto\UserRegistrationDto;
use App\Domain\User\Entity\User;
use App\Domain\User\Entity\UserInfo;
use App\Infrastructure\UserRepository;
use Assert\Assert;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFactory implements UserFactoryInterface
{
    private $users;
    private $passwordEncoder;

    public function __construct(UserRepository $users, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->users = $users;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function create(UserRegistrationDto $registrationDto): User
    {
        Assert::that($registrationDto->email)->email();
        Assert::that($registrationDto->password)->string();

        $user = new User();
        $user->setUsername(Email::fromString($registrationDto->email));
        $user->setPlainPassword($registrationDto->password);
        $user->setPassword(
            $this->passwordEncoder->encodePassword($user, $user->getPlainPassword())
        );
        $user->setUserInfo($this->createUserInfo($registrationDto, $user));

        return $user;
    }

    private function createUserInfo(UserRegistrationDto $registrationDto, User $user): UserInfo
    {
        $userInfo = new UserInfo();
        $userInfo->setUser($user);
        $userInfo->setName($registrationDto->name);

        return $userInfo;
    }
}
