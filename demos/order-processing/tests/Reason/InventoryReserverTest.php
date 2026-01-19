<?php

declare(strict_types=1);

namespace Be\App\Tests\Reason;

use Be\App\Moment\Potential\InventoryReservation;
use Be\App\Reason\InventoryReserver;
use PHPUnit\Framework\TestCase;

class InventoryReserverTest extends TestCase
{
    private InventoryReserver $reserver;

    protected function setUp(): void
    {
        $this->reserver = new InventoryReserver();
    }

    public function testLockReturnsMoment(): void
    {
        $reservation = $this->reserver->lock('WH-TOKYO-01', 'PROD-001', 5);

        $this->assertInstanceOf(InventoryReservation::class, $reservation);
        $this->assertStringStartsWith('RSV-TOKYO-', $reservation->reservationId);
    }

    public function testLockCreatesUniqueIds(): void
    {
        $reservation1 = $this->reserver->lock('WH-TOKYO-01', 'PROD-001', 5);
        $reservation2 = $this->reserver->lock('WH-TOKYO-01', 'PROD-001', 5);

        $this->assertNotSame($reservation1->reservationId, $reservation2->reservationId);
    }

    public function testMomentCanBeRealized(): void
    {
        $reservation = $this->reserver->lock('WH-TOKYO-01', 'PROD-001', 5);

        // Realize the potential
        $reservation->be();

        // No exception means success
        $this->assertTrue(true);
    }
}
