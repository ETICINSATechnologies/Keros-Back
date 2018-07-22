<?php

namespace KerosTest\Address;

use Keros\Entities\core\Address;
use Keros\Entities\Core\Country;
use PHPUnit\Framework\TestCase;


final class AddressTest extends TestCase
{
    public function testNewAddressShouldBeInstanceOfAddress()
    {
        $this->assertInstanceOf(Address::class,
                                new Address("18 rue du master", "", 69100, "Lyon"));
    }

    public function testAddressShouldCreateWithParams()
    {
        $address = new Address("18 rue du master", "", 69000, "Lyon");
        $country = new Country("Eldorado");
        $address->setCountry($country);

        $this->assertInstanceOf(Address::class, $address);
        $this->assertEquals("18 rue du master", $address->getLine1());
        $this->assertEquals("", $address->getLine2());
        $this->assertEquals(69000, $address->getPostalCode());
        $this->assertEquals("Lyon", $address->getCity());
        $this->assertEquals("Eldorado", $address->getCountry()->getLabel());
        $this->assertNull($address->getCountry()->getId());
        $this->assertNull($address->getId());
    }
}