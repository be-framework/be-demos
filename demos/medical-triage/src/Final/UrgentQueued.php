<?php

declare(strict_types=1);

namespace Be\Demo\MedicalTriage\Final;

use Be\Demo\MedicalTriage\Moment\QueuePositioned;
use Ray\Di\Di\Inject;

/**
 * Urgent Queued - Final (urgent triage path)
 *
 * Branching metamorphosis: PatientInput -> UrgentQueued
 * QueuePositioned is a pure data Moment with no Potential.
 * No be() call needed - just data assembly.
 *
 * @link https://schema.org/MedicalClinic
 */
final readonly class UrgentQueued
{
    public string $queueId;
    public string $status;

    public function __construct(
        #[Inject] public QueuePositioned $queuePositioned,
    ) {
        // No be() - QueuePositioned has no Potential to realize
        $this->queueId = sprintf(
            'QUE-%s-%04d',
            date('Ymd'),
            $this->queuePositioned->queuePosition
        );
        $this->status = 'queued';
    }
}
