<?php

namespace KerosTest\Core;

use Keros\Error\KerosException;
use Keros\Tools\Validator;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    public function testValidIdAsText()
    {
        $this->assertSame(Validator::requiredId("2"), 2);
        $this->assertSame(Validator::optionalId("2"), 2);
        $this->assertSame(Validator::requiredId(2), 2);
        $this->assertSame(Validator::optionalId(2), 2);
    }

    public function testInvalidOptionalIdAsText()
    {
        $this->expectException(KerosException::class);
        Validator::optionalId("a");
    }

    public function testInvalidRequiredIdAsText()
    {
        $this->expectException(KerosException::class);
        Validator::RequiredId("a");
    }

    public function testValidIntAsText()
    {
        $this->assertSame(Validator::requiredInt("2"), 2);
        $this->assertSame(Validator::optionalInt("2"), 2);
        $this->assertSame(Validator::requiredInt(2), 2);
        $this->assertSame(Validator::optionalInt(2), 2);
    }

    public function testInvalidOptionalIntAsText()
    {
        $this->expectException(KerosException::class);
        Validator::optionalInt("a");
    }

    public function testInvalidRequiredIntAsText()
    {
        $this->expectException(KerosException::class);
        Validator::RequiredInt("a");
    }

    public function testValidFloatAsText()
    {
        $this->assertSame(Validator::requiredFloat("2.2"), 2.2);
        $this->assertSame(Validator::optionalFloat("2.2"), 2.2);
        $this->assertSame(Validator::requiredFloat(2.2), 2.2);
        $this->assertSame(Validator::optionalFloat(2.2), 2.2);
    }

    public function testInvalidOptionalFloatAsText()
    {
        $this->expectException(KerosException::class);
        Validator::optionalFloat("a");
    }

    public function testInvalidRequiredFloatAsText()
    {
        $this->expectException(KerosException::class);
        Validator::RequiredFloat("a");
    }

    public function testValidBoolAsText()
    {
        $this->assertSame(Validator::requiredBool("true"), true);
        $this->assertSame(Validator::optionalBool("false"), false);
        $this->assertSame(Validator::requiredBool("True"), true);
        $this->assertSame(Validator::optionalBool("False"), false);
        $this->assertSame(Validator::requiredBool("TRUE"), true);
        $this->assertSame(Validator::optionalBool("FALSE"), false);
        $this->assertSame(Validator::requiredBool(true), true);
        $this->assertSame(Validator::optionalBool(false), false);
    }

    public function testInvalidOptionalBoolAsText()
    {
        $this->expectException(KerosException::class);
        Validator::optionalBool("a");
    }

    public function testInvalidRequiredBoolAsText()
    {
        $this->expectException(KerosException::class);
        Validator::RequiredBool("a");
    }

    public function testValidEmailShouldReturnSame(): void
    {
        $this->assertEquals(
            Validator::requiredEmail("test@test.com"),
            "test@test.com"
        );
    }

    public function testInvalidEmailShouldThrowError(): void
    {
        $this->expectException(KerosException::class);

        Validator::requiredEmail('invalid');
    }

    public function testEmailWithWhitespaceShouldTrimmed(): void
    {
        $this->assertEquals(
            'user@example.com',
            Validator::requiredEmail('   user@example.com   ')
        );
    }
}