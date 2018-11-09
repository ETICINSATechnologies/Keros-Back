<?php

namespace KerosTest\Contact;

use Keros\Entities\ua\Contact;
use PHPUnit\Framework\TestCase;


class ContactTest extends TestCase
{
    public function testNewContactShouldBeInstanceOfContact()
    {
        $this->assertInstanceOf(Contact::class,
                                new Contact("Marah", "Tainturier", 1, 1,
                                            "marah.laurent@gmail.com", false));
    }

    public function testContactShouldCreateWithParams()
    {
        $Contact = new Contact("Marah", "Tainturier", 1, 1,
                               "marah.laurent@gmail.com",true);

        $this->assertInstanceOf(Contact::class, $Contact);
        $this->assertEquals($Contact->getFirstName(), "Marah");
        $this->assertEquals($Contact->getLastName(), "Tainturier");
        $this->assertNotNull($Contact->getGender());
        $this->assertNotNull($Contact->getFirm());
        $this->assertEquals($Contact->getEmail(), "marah.laurent@gmail.com");

        $this->assertNull($Contact->getTelephone());
        $this->assertNull($Contact->getCellphone());
        $this->assertEquals($Contact->getOld(), true);

    }
}