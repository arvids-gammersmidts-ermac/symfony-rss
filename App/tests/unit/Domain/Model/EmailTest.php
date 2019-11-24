<?php

namespace Tests\Unit\Domain\Model;

use App\Domain\Model\Email;

class EmailTest extends \Codeception\Test\Unit
{
    /**
     * @test
     */
    public function it_creates_from_string()
    {
        $email = Email::fromString('test@domain.com');

        $this->assertEquals('test@domain.com', $email->email());
        $this->assertEquals('test@domain.com', (string) $email);
    }
}
