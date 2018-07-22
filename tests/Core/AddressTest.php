<?php

namespace KerosTest\Address;

use Keros\Entities\core\Address;
use PHPUnit\Framework\TestCase;

final class AddressTest extends TestCase
{
    public function testNewAddressShouldBeInstanceOfAddress()
    {
        # @TODO: modify this test
        # $this->assertInstanceOf(Address::class,
        #                        new Address("18 rue du master", "", 69100, "Lyon", 62));
    }

    /*public function testAddressShouldCreateWithParams()
    {
        $address = new Address("18 rue du master", "", 69000, "Lyon", 62);
        $this->assertInstanceOf(Address::class, $address);
        $this->assertEquals("18 rue du master", $address->getLine1());
        $this->assertEquals("", $address->getLine2());
        $this->assertEquals(69000, $address->getPostalCode());
        $this->assertEquals("Lyon", $address->getCity());
        $this->assertEquals(62, $address->getCountry());
        $this->assertNull($address->getId());
    }*/
}