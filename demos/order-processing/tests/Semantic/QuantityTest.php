<?php

declare(strict_types=1);

namespace Be\Pattern\OrderProcessing\Tests\Semantic;

use Be\Pattern\OrderProcessing\Semantic\Quantity;
use Be\Pattern\OrderProcessing\Exception\InvalidQuantityException;
use PHPUnit\Framework\TestCase;

class QuantityTest extends TestCase
{
    private Quantity $semantic;

    protected function setUp(): void
    {
        $this->semantic = new Quantity();
    }

    public function testValidQuantity(): void
    {
        $this->semantic->validate(1);
        $this->semantic->validate(50);
        $this->semantic->validate(99);
        $this->assertTrue(true);
    }

    public function testInvalidQuantityZero(): void
    {
        $this->expectException(InvalidQuantityException::class);
        $this->semantic->validate(0);
    }

    public function testInvalidQuantityNegative(): void
    {
        $this->expectException(InvalidQuantityException::class);
        $this->semantic->validate(-1);
    }

    public function testInvalidQuantityTooHigh(): void
    {
        $this->expectException(InvalidQuantityException::class);
        $this->semantic->validate(100);
    }
}
