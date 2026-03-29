<?php

declare(strict_types=1);

namespace Be\Demo\MedicalTriage\Moment;

use Ray\InputQuery\Attribute\Input;

/**
 * Queue Positioned - Pure data Moment (no Potential)
 *
 * Unlike BedAssigned and TeamAlerted, this Moment has no side effects.
 * It simply calculates queue position and estimated wait time.
 * No MomentInterface - no be() needed.
 */
final readonly class QueuePositioned
{
    public int $queuePosition;
    public int $estimatedWaitMinutes;

    public function __construct(
        #[Input] public string $patientId,
        #[Input] public string $triageLevel,
    ) {
        // Calculate queue position based on triage level
        $this->queuePosition = match ($triageLevel) {
            'urgent' => random_int(1, 5),
            default => random_int(5, 20),
        };

        // Estimate wait time based on queue position
        $this->estimatedWaitMinutes = $this->queuePosition * 15;
    }
}
