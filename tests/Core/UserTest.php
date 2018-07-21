<?php

namespace KerosTest\Core;

use Keros\Entities\Core\User;
use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase
{
    public function testNewUserShouldBeInstanceOfUser()
    {
        $this->assertInstanceOf(User::class, new User("toto"));
    }

    public function testUserShouldCreateWithParams()
    {
        $user = new User("toto");
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals("toto", $user->getUsername());
        $this->assertNull($user->getId());
    }
}