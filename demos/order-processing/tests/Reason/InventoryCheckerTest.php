<?php

declare(strict_types=1);

namespace Be\Pattern\OrderProcessing\Tests\Reason;

use Be\Pattern\OrderProcessing\Reason\InventoryChecker;
use PHPUnit\Framework\TestCase;

class InventoryCheckerTest extends TestCase
{
    private InventoryChecker $checker;

    protected function setUp(): void
    {
        $this->checker = new InventoryChecker();
    }

    public function testCheckAvailable(): void
    {
        $this->assertTrue($this->checker->check('WH-TOKYO-01', 'A001', 5));
    }

    public function testCheckAvailableMaxQuantity(): void
    {
        $this->assertTrue($this->checker->check('WH-TOKYO-01', 'A001', 10));
    }

    public function testCheckUnavailable(): void
    {
        $this->assertFalse($this->checker->check('WH-TOKYO-01', 'A001', 11));
    }
}
