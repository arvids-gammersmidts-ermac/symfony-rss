<?php

namespace Tests\Unit\Application\Dto;

use App\Application\Dto\Assembler;
use App\Domain\User\Entity\User;
use App\Domain\User\Entity\UserInfo;

class AssemblerTest extends \Codeception\Test\Unit
{
    /**
     * @test
     */
    public function it_converts_user_to_dto()
    {
        $user = new User();
        $user->setId(1);
        $user->setUsername('test.man@domain.com');
        $userInfo = new UserInfo();
        $userInfo->setName('Tester man');
        $user->setUserInfo($userInfo);

        $assembler = new Assembler();
        $dto = $assembler->toUserDto($user);

        $this->assertEquals('test.man@domain.com', $dto->email);
        $this->assertEquals('Tester man', $dto->name);
    }
}
