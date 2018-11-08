<?php

namespace KerosTest\Contact;

use Keros\Entities\ua\Contact;
use PHPUnit\Framework\TestCase;


class ContactTest extends TestCase
{
    public function testNewContactShouldBeInstanceOfContact()
    {
        $this->assertInstanceOf(Contact::class,
                                new Contact("Marah", "Tainturier", 1, 1, "marah.laurent@gmail.com","0658984503","0175389516","chef de projet",
                                            "rien a signaler",true));
    }

    public function testContactShouldCreateWithParams()
    {
        $Contact = new Contact("Marah", "Tainturier", 1, 1, "marah.laurent@gmail.com","0658984503","0175389516","chef de projet",
                                            "rien a signaler",true);

        $this->assertInstanceOf(Contact::class, $Contact);
        $this->assertEquals("Marah", $Contact->getFirstName());
        $this->assertEquals("Tainturier", $Contact->getLastName());
        $this->assertEquals(1, $Contact->getGenderId());
        $this->assertEquals(1, $Contact->getFirmId());
        $this->assertEquals("marah.laurent@gmail.com", $Contact->getEmail());

        $this->assertEquals("0658984503", $Contact->getTelephone());
        $this->assertEquals("0175389516", $Contact->getCellphone());
        $this->assertEquals("chef de projet", $Contact->getPosition());
        $this->assertEquals("rien a signaler", $Contact->getNotes());
        $this->assertEquals(true, $Contact->getOld());

    }
}