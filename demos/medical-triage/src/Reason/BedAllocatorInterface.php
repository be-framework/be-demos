<?php

declare(strict_types=1);

namespace Be\Demo\MedicalTriage\Reason;

use Be\Demo\MedicalTriage\Moment\Potential\BedReservation;

/**
 * Bed Allocator Interface
 *
 * Enables testability through dependency injection.
 */
interface BedAllocatorInterface
{
    public function allocate(string $patientId): BedReservation;
}
