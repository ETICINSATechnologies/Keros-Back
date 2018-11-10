<?php

namespace KerosTest\Field;

use Keros\Entities\Ua\Field;

use PHPUnit\Framework\TestCase;


class FieldTest extends TestCase
{
    public function testNewFieldShouldBeInstanceOfField()
    {
        $this->assertInstanceOf(Field::class,
                                new Field("18 rue du master"));
    }


}