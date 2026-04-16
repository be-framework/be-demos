<?php

declare(strict_types=1);

namespace Be\Pattern\OrderProcessing\Tests\Moment;

use Be\Pattern\OrderProcessing\Moment\InventoryReserved;
use Be\Pattern\OrderProcessing\Reason\InventoryReserver;
use PHPUnit\Framework\TestCase;

class InventoryReservedTest extends TestCase
{
    public function testInventoryReserved(): void
    {
        $reserver = new InventoryReserver();
        $moment = new InventoryReserved('PROD-001', 5, 'WH-TOKYO-01', $reserver);

        $this->assertSame('PROD-001', $moment->productId);
        $this->assertSame(5, $moment->quantity);
        $this->assertSame('WH-TOKYO-01', $moment->warehouseId);
        $this->assertStringStartsWith('RSV-TOKYO-', $moment->reservation->reservationId);
    }

    public function testMomentCanBeRealized(): void
    {
        $reserver = new InventoryReserver();
        $moment = new InventoryReserved('PROD-001', 5, 'WH-TOKYO-01', $reserver);

        // Realize the Moment
        $moment->be();

        // No exception means success
        $this->assertTrue(true);
    }
}
