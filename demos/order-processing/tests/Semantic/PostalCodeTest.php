<?php

declare(strict_types=1);

namespace Be\Pattern\OrderProcessing\Tests\Semantic;

use Be\Pattern\OrderProcessing\Semantic\PostalCode;
use Be\Pattern\OrderProcessing\Exception\InvalidPostalCodeException;
use PHPUnit\Framework\TestCase;

class PostalCodeTest extends TestCase
{
    private PostalCode $semantic;

    protected function setUp(): void
    {
        $this->semantic = new PostalCode();
    }

    public function testValidPostalCode(): void
    {
        $this->semantic->validate('1500001');
        $this->assertTrue(true);
    }

    public function testValidPostalCodeWithHyphen(): void
    {
        $this->semantic->validate('150-0001');
        $this->assertTrue(true);
    }

    public function testValidPostalCodeWithSpaces(): void
    {
        $this->semantic->validate('150 0001');
        $this->assertTrue(true);
    }

    public function testInvalidPostalCodeTooShort(): void
    {
        $this->expectException(InvalidPostalCodeException::class);
        $this->semantic->validate('15000');
    }

    public function testInvalidPostalCodeWithLetters(): void
    {
        $this->expectException(InvalidPostalCodeException::class);
        $this->semantic->validate('150-ABCD');
    }
}
