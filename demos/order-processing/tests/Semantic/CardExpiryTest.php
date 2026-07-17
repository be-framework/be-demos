<?php

declare(strict_types=1);

namespace Be\Pattern\OrderProcessing\Tests\Semantic;

use Be\Pattern\OrderProcessing\Semantic\CardExpiry;
use Be\Pattern\OrderProcessing\Exception\InvalidCardExpiryException;
use PHPUnit\Framework\TestCase;

class CardExpiryTest extends TestCase
{
    private CardExpiry $semantic;

    protected function setUp(): void
    {
        $this->semantic = new CardExpiry();
    }

    public function testValidFutureExpiry(): void
    {
        $this->semantic->validate('12/49');
        $this->addToAssertionCount(1);
    }

    public function testCurrentMonthIsStillValid(): void
    {
        // A card expiring this month is valid through the last day of the month
        $this->semantic->validate(date('m/y'));
        $this->addToAssertionCount(1);
    }

    public function testExpiredCardThrowsException(): void
    {
        $this->expectException(InvalidCardExpiryException::class);
        $this->semantic->validate('01/20');
    }

    public function testInvalidMonthThrowsException(): void
    {
        $this->expectException(InvalidCardExpiryException::class);
        $this->semantic->validate('13/49');
    }

    public function testInvalidFormatThrowsException(): void
    {
        $this->expectException(InvalidCardExpiryException::class);
        $this->semantic->validate('12-49');
    }
}
