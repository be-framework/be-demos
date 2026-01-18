<?php

declare(strict_types=1);

namespace Be\App\Tests\Being\Inventory;

use Be\App\Being\Inventory\QuantityChecked;
use Be\App\Reason\InventoryChecker;
use PHPUnit\Framework\TestCase;

class QuantityCheckedTest extends TestCase
{
    public function testQuantityAvailable(): void
    {
        $checker = new InventoryChecker();
        $being = new QuantityChecked('A001', 5, 'WH-TOKYO-01', $checker);

        $this->assertSame('A001', $being->productId);
        $this->assertSame(5, $being->quantity);
        $this->assertSame('WH-TOKYO-01', $being->warehouseId);
        $this->assertTrue($being->available);
    }

    public function testQuantityUnavailable(): void
    {
        $checker = new InventoryChecker();
        $being = new QuantityChecked('A001', 15, 'WH-TOKYO-01', $checker);

        $this->assertFalse($being->available);
    }
}
