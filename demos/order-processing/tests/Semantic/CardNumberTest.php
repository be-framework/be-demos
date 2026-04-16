<?php

declare(strict_types=1);

namespace Be\Pattern\OrderProcessing\Tests\Semantic;

use Be\Pattern\OrderProcessing\Semantic\CardNumber;
use Be\Pattern\OrderProcessing\Exception\InvalidCardNumberException;
use PHPUnit\Framework\TestCase;

class CardNumberTest extends TestCase
{
    private CardNumber $semantic;

    protected function setUp(): void
    {
        $this->semantic = new CardNumber();
    }

    public function testValidCardNumber(): void
    {
        // Luhn-valid test card number
        $this->semantic->validate('4111111111111111');
        $this->assertTrue(true);
    }

    public function testValidCardNumberWithSpaces(): void
    {
        $this->semantic->validate('4111 1111 1111 1111');
        $this->assertTrue(true);
    }

    public function testValidCardNumberWithDashes(): void
    {
        $this->semantic->validate('4111-1111-1111-1111');
        $this->assertTrue(true);
    }

    public function testInvalidCardNumberTooShort(): void
    {
        $this->expectException(InvalidCardNumberException::class);
        $this->semantic->validate('411111');
    }

    public function testInvalidCardNumberFailsLuhn(): void
    {
        $this->expectException(InvalidCardNumberException::class);
        $this->semantic->validate('4111111111111112');
    }
}
