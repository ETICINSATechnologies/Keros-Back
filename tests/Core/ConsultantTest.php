<?php

namespace KerosTest\Member;

use DateTime;
use Keros\Entities\Core\Department;
use Keros\Entities\Core\Gender;
use Keros\Entities\core\Consultant;
use Keros\Entities\Core\Position;
use PHPUnit\Framework\TestCase;


class ConsultantTest extends TestCase
{
    public function testNewConsultantShouldBeInstanceOfConsultant()
    {
        $this->assertInstanceOf(Consultant::class,
            new Consultant(
                "Basmah",
                "Maiga",
                new DateTime("11/26/1998"),
                "0675385495",
                "basmah.maiga@gmail.com",
                "2018",
                new Gender("Femme"),
                new Department(1, "IF", "Informatique"),
                "Google",
                "http://photoprofile.jpg"));
    }

    public function testConsultantShouldCreateWithParams()
    {
        $consultant = new Consultant("Basmah",
            "Maiga",
            new DateTime("11/26/1998"),
            "0675385495",
            "basmah.maiga@gmail.com",
            "2018",
            new Gender("Femme"),
            new Department(1, "IF", "Informatique"),
            "Google",
            "http://photoprofile.jpg");


        $this->assertInstanceOf(Consultant::class, $consultant);
        $this->assertEquals("Basmah", $consultant->getFirstName());
        $this->assertEquals("Maiga", $consultant->getLastName());
        $this->assertEquals(new DateTime("11/26/1998"), $consultant->getBirthday());
        $this->assertEquals("0675385495", $consultant->getTelephone());
        $this->assertEquals("basmah.maiga@gmail.com", $consultant->getEmail());
        $this->assertEquals("2018", $consultant->getSchoolYear());
        $this->assertEquals("Google", $consultant->getCompany());
        $this->assertEquals("http://photoprofile.jpg", $consultant->getProfilePicture());
    }
}
