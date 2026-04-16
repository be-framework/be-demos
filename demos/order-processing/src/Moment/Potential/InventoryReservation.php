<?php

declare(strict_types=1);

namespace Be\Pattern\OrderProcessing\Moment\Potential;

use Be\Pattern\OrderProcessing\Moment\MomentInterface;

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

    private bool $realized = false;

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
        if ($this->realized) {
            return; // Already realized - idempotent
        }

        $this->realized = true;
        ($this->realize)();
    }
}
