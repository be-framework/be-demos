<?php

declare(strict_types=1);

namespace Be\Demo\MedicalTriage\Reason;

use Be\Demo\MedicalTriage\Moment\Potential\BedReservation;

/**
 * Bed Allocator - Reason (stateless gateway)
 *
 * Creates BedReservation moments that can be realized.
 */
final class BedAllocator implements BedAllocatorInterface
{
    public function allocate(string $patientId): BedReservation
    {
        // Create provisional reservation ID
        $reservationId = sprintf(
            'BED-RSV-%s-%s',
            date('YmdHis'),
            substr(md5($patientId . time()), 0, 6)
        );

        // Return Moment with realize callback
        return new BedReservation(
            $reservationId,
            fn () => $this->confirm($patientId),
        );
    }

    private function confirm(string $patientId): string
    {
        // External system call to confirm bed assignment
        // In production: return $this->api->assignBed($patientId);
        return sprintf('ER-BED-%03d', random_int(1, 50));
    }
}
