<?php

declare(strict_types=1);

namespace Be\App\Moment\Potential;

use Be\App\Moment\MomentInterface;

/**
 * Inventory Reservation - potential to confirm inventory hold
 *
 * Born from InventoryReserver::lock()
 * Realized by be() → confirms the reservation
 */
final class InventoryReservation implements MomentInterface
{
    /** @var callable(): void */
    private $realize;

    /**
     * @param callable(): void $realize
     */
    public function __construct(
        public readonly string $reservationId,
        callable $realize,
    ) {
        $this->realize = $realize;
    }

    public function be(): void
    {
        ($this->realize)();
    }
}
