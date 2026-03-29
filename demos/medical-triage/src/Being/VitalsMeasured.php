<?php

declare(strict_types=1);

namespace Be\Demo\MedicalTriage\Being;

use Be\Demo\MedicalTriage\Reason\VitalsAssessor;
use Ray\Di\Di\Inject;
use Ray\InputQuery\Attribute\Input;

/**
 * Vitals Measured - Being (intermediate state)
 *
 * Assesses all vital signs and produces an overall severity level.
 * This Being feeds into TriageLevelDetermined.
 */
final readonly class VitalsMeasured
{
    /** @var string 'critical'|'moderate'|'stable' */
    public string $vitalsSeverity;

    public function __construct(
        #[Input] public float $temperature,
        #[Input] public int $heartRate,
        #[Input] public int $bloodPressureSystolic,
        #[Input] public int $bloodPressureDiastolic,
        #[Inject] VitalsAssessor $assessor
    ) {
        $this->vitalsSeverity = $assessor->assess(
            $temperature,
            $heartRate,
            $bloodPressureSystolic,
            $bloodPressureDiastolic
        );
    }
}
