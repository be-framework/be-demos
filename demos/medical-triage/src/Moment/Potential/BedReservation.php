<?php

declare(strict_types=1);

namespace Be\Demo\MedicalTriage\Moment\Potential;

use Be\Demo\MedicalTriage\Moment\MomentInterface;

/**
 * Bed Reservation - potential to confirm bed assignment
 *
 * Born from BedAllocator::allocate()
 * Realized by be() -> confirms the bed reservation
 */
final class BedReservation implements MomentInterface
{
    /** @var callable(): string */
    private $realize;

    private ?string $bedNumber = null;

    /**
     * @param callable(): string $realize
     */
    public function __construct(
        public readonly string $reservationId,
        callable $realize,
    ) {
        $this->realize = $realize;
    }

    public function be(): void
    {
        if ($this->bedNumber !== null) {
            return; // Already realized - idempotent
        }

        $this->bedNumber = ($this->realize)();
    }

    public function getBedNumber(): ?string
    {
        return $this->bedNumber;
    }
}
