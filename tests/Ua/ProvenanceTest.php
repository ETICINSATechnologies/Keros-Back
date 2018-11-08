<?php

namespace KerosTest\Provenance;

use Keros\Entities\ua\Provenance;

use PHPUnit\Framework\TestCase;


class ProvenanceTest extends TestCase
{
    public function testNewProvenanceShouldBeInstanceOfProvenance()
    {
        $this->assertInstanceOf(Provenance::class,
                                new Provenance("18 rue du master"));
    }


}