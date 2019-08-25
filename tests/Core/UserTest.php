<?php

namespace KerosTest\Core;

use Keros\Entities\Core\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testNewUserShouldBeInstanceOfUser()
    {
        $date = new \DateTime();
        $this->assertInstanceOf(User::class, new User("james bond", "JamesBond007", $date, $date, false, $date));
    }

    public function testUserShouldCreateWithParams()
    {
        $date = new \DateTime();
        $user =  new User("james bond", "JamesBond007", $date, $date, false, $date);
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals("james bond", $user->getUsername());
        $this->assertEquals("JamesBond007", $user->getPassword());
        $this->assertEquals($date, $user->getLastConnectedAt());
        $this->assertEquals($date, $user->getCreatedAt());
        $this->assertEquals(false, $user->getDisabled());
        $this->assertEquals($date, $user->getExpiresAt());
        $this->assertNull($user->getId());
    }
}