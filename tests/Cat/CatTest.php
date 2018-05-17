<?php


use Keros\Entities\Cat\Cat;
use PHPUnit\Framework\TestCase;

final class CatTest extends TestCase
{
    public function testCatShouldCreateWithoutParams(){
        $this->assertInstanceOf(Cat::class, new Cat());
    }

    public function testCatShouldCreateWithOneParam(){
        $cat = new Cat(null, "tom");
        $this->assertInstanceOf(Cat::class, $cat);
        $this->assertEquals("tom", $cat->name);
        $this->assertNull($cat->id);
    }

    public function testCatShouldCreateWithAllParams(){
        $cat = new Cat(5, "tom", 5.75);
        $this->assertInstanceOf(Cat::class, $cat);
        $this->assertEquals(5, $cat->id);
        $this->assertEquals("tom", $cat->name);
        $this->assertEquals(5.75, $cat->height);
    }
}