<?php

declare(strict_types=1);

namespace Be\Demo\MedicalTriage\Final;

use Be\Demo\MedicalTriage\Being\Path\UrgentPath;
use Be\Demo\MedicalTriage\Moment\QueuePositioned;
use Ray\InputQuery\Attribute\Input;

/**
 * Urgent Queued - Final (urgent triage path)
 *
 * Selected by the Be Framework when the preceding TriageLevelDetermined sets
 * $being to an {@see UrgentPath}. QueuePositioned is a pure data Moment with
 * no Potential - no be() call needed, just data assembly.
 *
 * @link https://schema.org/MedicalClinic
 */
final readonly class UrgentQueued
{
    public QueuePositioned $queuePositioned;
    public string $queueId;
    public string $status;

    public function __construct(
        #[Input] public UrgentPath $being,
        #[Input] public string $patientId,
        #[Input] public string $triageLevel,
    ) {
        $this->queuePositioned = new QueuePositioned($patientId, $triageLevel);

        $this->queueId = sprintf(
            'QUE-%s-%04d',
            date('Ymd'),
            $this->queuePositioned->queuePosition,
        );
        $this->status = 'queued';
    }
}
