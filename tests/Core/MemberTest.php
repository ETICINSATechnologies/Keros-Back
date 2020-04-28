<?php

namespace KerosTest\Member;

use DateTime;
use Keros\Entities\Core\Department;
use Keros\Entities\Core\Gender;
use Keros\Entities\core\Member;
use Keros\Entities\Core\Position;
use PHPUnit\Framework\TestCase;


class MemberTest extends TestCase
{
    public function testNewMemberShouldBeInstanceOfMember()
    {
        $this->assertInstanceOf(Member::class,
            new Member(
                "Basmah",
                "Maiga",
                new DateTime("11/26/1998"),
                "0675385495",
                "basmah.maiga@gmail.com",
                "2018",
                new Gender("Femme"),
                new Department(1, "IF", "Informatique"),
                "Google",
                null,
                true,
                new DateTime("9/1/2019"),
                false,
                array(),
                "etic@etic-insra.com",
                new DateTime("9/1/2019")
            )
        );
    }

    public function testMemberShouldCreateWithParams()
    {
        $member = new Member("Basmah",
            "Maiga",
            new DateTime("11/26/1998"),
            "0675385495",
            "basmah.maiga@gmail.com",
            "2018",
            new Gender("Femme"),
            new Department(1, "IF", "Informatique"),
            "Google",
            "randomstring.jpg",
            true,
            new DateTime("9/1/2019"),
            true,
            array(),
            "etic@etic-insra.com",
            new DateTime("10/1/2019")
        );


        $this->assertInstanceOf(Member::class, $member);
        $this->assertEquals("Basmah", $member->getFirstName());
        $this->assertEquals("Maiga", $member->getLastName());
        $this->assertEquals(new DateTime("11/26/1998"), $member->getBirthday());
        $this->assertEquals("0675385495", $member->getTelephone());
        $this->assertEquals("basmah.maiga@gmail.com", $member->getEmail());
        $this->assertEquals("2018", $member->getSchoolYear());
        $this->assertEquals("Google", $member->getCompany());
        $this->assertEquals("randomstring.jpg", $member->getProfilePicture());
        $this->assertEquals(true, $member->isDroitImage());
        $this->assertEquals(true, $member->getIsAlumni());
        $this->assertEquals(new DateTime("9/1/2019"), $member->getCreatedDate());
    }
}