<?php

namespace KerosTest\Cat;

use Keros\Entities\Cat\Cat;
use PHPUnit\Framework\TestCase;

final class CatTest extends TestCase
{
    public function testNewCatShouldBeInstanceOfCat()
    {
        $this->assertInstanceOf(Cat::class, new Cat("tom", 2.75));
    }

    public function testCatShouldCreateWithParams()
    {
        $cat = new Cat("john", 7.41);
        $this->assertInstanceOf(Cat::class, $cat);
        $this->assertEquals("john", $cat->getName());
        $this->assertEquals(7.41, $cat->getHeight());
        $this->assertNull($cat->getId());
    }
}