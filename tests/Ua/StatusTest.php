<?php

namespace KerosTest\Status;

use Keros\Entities\Ua\Status;

use PHPUnit\Framework\TestCase;


class StatusTest extends TestCase
{
    public function testNewStatusShouldBeInstanceOfStatus()
    {
        $this->assertInstanceOf(Status::class,
                                new Status("18 rue du master"));
    }


}