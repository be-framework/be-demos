<?php

declare(strict_types=1);

namespace Be\Demo\MedicalTriage\Final;

use Be\Demo\MedicalTriage\Reason\UrgentCase;
use Ray\InputQuery\Attribute\Input;

/**
 * Urgent Queued - Final (urgent triage path)
 *
 * Selected by the Be Framework when the preceding TriageLevelDetermined sets
 * its $being discriminator to an {@see UrgentCase}. The Final delegates queue
 * assignment to that strategy via `$being->queue(...)`.
 *
 * @link https://schema.org/MedicalClinic
 */
final readonly class UrgentQueued
{
    public string $queueId;
    public int $queuePosition;
    public string $status;
    public string $triageCode;

    public function __construct(
        #[Input] public UrgentCase $being,
        #[Input] public string $patientId,
    ) {
        $result = $being->queue($patientId);
        $this->queueId = $result['queueId'];
        $this->queuePosition = $result['queuePosition'];
        $this->status = $result['status'];
        $this->triageCode = $being->triageCode;
    }
}
