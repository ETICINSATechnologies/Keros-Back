<?php

namespace KerosTest\Core;

use Keros\Error\KerosException;
use Keros\Tools\Validator;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
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