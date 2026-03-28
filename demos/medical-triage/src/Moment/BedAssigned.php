<?php

declare(strict_types=1);

namespace Be\Demo\MedicalTriage\Moment;

use Be\Demo\MedicalTriage\Moment\Potential\BedReservation;
use Be\Demo\MedicalTriage\Reason\BedAllocatorInterface;
use Ray\Di\Di\Inject;
use Ray\InputQuery\Attribute\Input;

/**
 * Bed Assigned - Moment (part + potential)
 *
 * Part of EmergencyAdmitted, holding the potential to confirm bed reservation.
 */
final readonly class BedAssigned implements MomentInterface
{
    public BedReservation $reservation;

    public function __construct(
        #[Input] public string $patientId,
        #[Inject] BedAllocatorInterface $allocator,
    ) {
        // Born: create potential (reserve emergency bed)
        $this->reservation = $allocator->allocate($patientId);
    }

    public function be(): void
    {
        $this->reservation->be();
    }
}
