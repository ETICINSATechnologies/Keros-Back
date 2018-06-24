<?php

namespace KerosTest\Core;

use Keros\Error\KerosException;
use Keros\Tools\Validator;
use PHPUnit\Framework\TestCase;

final class ValidatorTest extends TestCase
{
    public function testValidEmailShouldReturnSame(): void
    {
        $this->assertEquals(
            Validator::email("test@test.com"),
            "test@test.com"
        );
    }

    public function testInvalidEmailShouldThrowError(): void
    {
        $this->expectException(KerosException::class);

        Validator::email('invalid');
    }

    public function testEmailWithWhitespaceShouldTrimmed(): void
    {
        $this->assertEquals(
            'user@example.com',
            Validator::email('   user@example.com   ')
        );
    }
}