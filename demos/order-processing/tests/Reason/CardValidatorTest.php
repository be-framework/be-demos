<?php

declare(strict_types=1);

namespace Be\Pattern\OrderProcessing\Tests\Reason;

use Be\Pattern\OrderProcessing\Reason\CardValidator;
use PHPUnit\Framework\TestCase;

class CardValidatorTest extends TestCase
{
    private CardValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new CardValidator();
    }

    public function testValidFutureExpiry(): void
    {
        $futureYear = (int) date('y') + 1;
        $this->assertTrue($this->validator->validate('4111111111111111', "12/{$futureYear}"));
    }

    public function testValidCurrentMonthExpiry(): void
    {
        $currentMonth = date('m');
        $currentYear = date('y');
        $this->assertTrue($this->validator->validate('4111111111111111', "{$currentMonth}/{$currentYear}"));
    }

    public function testInvalidPastExpiry(): void
    {
        $this->assertFalse($this->validator->validate('4111111111111111', '01/20'));
    }

    public function testInvalidExpiryFormat(): void
    {
        $this->assertFalse($this->validator->validate('4111111111111111', '2025-12'));
    }

    public function testInvalidMonth(): void
    {
        $this->assertFalse($this->validator->validate('4111111111111111', '13/25'));
    }
}
