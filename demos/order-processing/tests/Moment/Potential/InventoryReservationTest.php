<?php

declare(strict_types=1);

namespace Be\Pattern\OrderProcessing\Tests\Moment\Potential;

use Be\Pattern\OrderProcessing\Moment\Potential\InventoryReservation;
use PHPUnit\Framework\TestCase;

class InventoryReservationTest extends TestCase
{
    public function testBeRealizesThePotential(): void
    {
        $confirmed = 0;
        $reservation = new InventoryReservation('RSV-TEST-001', function () use (&$confirmed): void {
            $confirmed++;
        });

        $reservation->be();

        $this->assertSame(1, $confirmed);
    }

    public function testBeIsIdempotent(): void
    {
        $confirmed = 0;
        $reservation = new InventoryReservation('RSV-TEST-001', function () use (&$confirmed): void {
            $confirmed++;
        });

        $reservation->be();
        $reservation->be();

        $this->assertSame(1, $confirmed);
    }
}
