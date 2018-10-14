<?php

namespace KerosTest\Member;

use Keros\Entities\core\Member;
use PHPUnit\Framework\TestCase;


final class MemberTest extends TestCase
{
    public function testNewMemberShouldBeInstanceOfMember()
    {
        $this->assertInstanceOf(Member::class,
                                new Member("Basmah", "Maiga", 14/06/1998, "0675385495","basmah.maiga@gmail.com","2018"));
    }

    public function testMemberShouldCreateWithParams()
    {
        $member= new Member("Basmah", "Maiga", "14/06/1998", "0675385495","basmah.maiga@gmail.com","2018");


        $this->assertInstanceOf(Member::class,  $member);
        $this->assertEquals("Basmah", $member->getFirstName());
        $this->assertEquals("Maiga", $member->getLastName());
        $this->assertEquals("14/06/1998", $member->getBirthDate());
        $this->assertEquals("0675385495", $member->getTelephone());
        $this->assertEquals("basmah.maiga@gmail.com", $member->getEmail());
        $this->assertEquals("2018", $member->getSchoolYear());
    }
}