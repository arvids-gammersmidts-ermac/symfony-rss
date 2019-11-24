<?php

namespace Tests\Unit\Application\Service;

use App\Application\Command\RegisterUser;
use App\Application\Dto\Assembler;
use App\Application\Dto\UserDto;
use App\Application\Dto\UserRegistrationDto;
use App\Application\Exception\InvalidPassword;
use App\Application\Exception\UserAlreadyExists;
use App\Application\Service\RegistrationService;
use App\Domain\Model\UserFactory;
use App\Domain\User\Entity\User;
use App\Domain\User\Entity\UserInfo;
use App\Infrastructure\UserRepository;
use PHPUnit\Framework\MockObject\MockObject;

class RegistrationServiceTest extends \Codeception\Test\Unit
{
    /**
     * @var UserFactory|MockObject
     */
    private $userFactory;

    /**
     * @var UserRepository|MockObject
     */
    private $users;

    /**
     * @var Assembler|MockObject
     */
    private $assembler;

    /**
     * @var RegistrationService
     */
    private $service;

    protected function _before()
    {
        $this->userFactory = $this->createMock(UserFactory::class);
        $this->users = $this->createMock(UserRepository::class);
        $this->assembler = $this->createMock(Assembler::class);
        $this->service = new RegistrationService($this->users, $this->userFactory, $this->assembler);
    }

    private function createUserTestMan(): User
    {
        $user = new User();
        $user->setId(1);
        $user->setUsername('test.man@domain.com');
        $userInfo = new UserInfo();
        $userInfo->setName('Tester man');
        $user->setUserInfo($userInfo);

        return $user;
    }

    private function createUserTestWoman(): User
    {
        $user = new User();
        $user->setId(1);
        $user->setUsername('test.woman@domain.com');
        $userInfo = new UserInfo();
        $userInfo->setName('Tester woman');
        $user->setUserInfo($userInfo);

        return $user;
    }

    /**
     * @test
     */
    public function it_throws_exception_when_user_exists_with_email()
    {
        $command = new RegisterUser();
        $command->user = new UserRegistrationDto();
        $command->user->email = 'test@domain.com';

        $this->users
            ->method('loadUserByUsername')
            ->with('test@domain.com')
            ->willReturn($this->createUserTestMan())
        ;

        $this->expectException(UserAlreadyExists::class);
        $this->expectExceptionMessage('User with email "test@domain.com" already exists.');

        $this->service->registerUser($command);
    }

    /**
     * @test
     */
    public function it_throws_exception_when_password_is_too_weak()
    {
        $command = new RegisterUser();
        $command->user = new UserRegistrationDto();
        $command->user->email = 'test@domain.com';
        $command->user->password = 'weak';

        $this->users
            ->method('loadUserByUsername')
            ->with('test@domain.com')
            ->willReturn(null)
        ;

        $this->expectException(InvalidPassword::class);
        $this->expectExceptionMessage('Your password must contain at least one number, one uppercase letter, one lowercase letter, and at least 8 or more characters');

        $this->service->registerUser($command);
    }

    /**
     * @test
     */
    public function it_registers_user()
    {
        $command = new RegisterUser();
        $command->user = new UserRegistrationDto();
        $command->user->name = 'Tester man';
        $command->user->email = 'test.man@domain.com';
        $command->user->password = 'CorrectPassword12345';

        $this->users
            ->method('loadUserByUsername')
            ->withConsecutive(
                ['test.man@domain.com'],
                ['test.woman@domain.com']
            )
            ->willReturnOnConsecutiveCalls(null, $creator = $this->createUserTestWoman())
        ;
        $this->userFactory
            ->expects($this->any())
            ->method('create')
            ->willReturn($user = $this->createUserTestMan())
        ;
        $this->users
            ->expects($this->once())
            ->method('save')
        ;
        $this->assembler
            ->expects($this->once())
            ->method('toUserDto')
            ->willReturn($dto = new UserDto())
        ;

        $this->assertEquals($dto, $this->service->registerUser($command));
    }
}
