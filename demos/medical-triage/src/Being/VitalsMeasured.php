<?php

declare(strict_types=1);

namespace Be\Demo\MedicalTriage\Being;

use Be\Demo\MedicalTriage\Reason\VitalsAssessor;
use Be\Framework\Attribute\Be;
use Ray\Di\Di\Inject;
use Ray\InputQuery\Attribute\Input;

/**
 * Vitals Measured - Being (intermediate state)
 *
 * Assesses all vital signs and produces an overall severity level.
 * All patient fields are re-declared as #[Input] pass-throughs so the
 * subsequent TriageLevelDetermined state can read them from this Being.
 */
#[Be(TriageLevelDetermined::class)]
final readonly class VitalsMeasured
{
    /** @var string 'critical'|'moderate'|'stable' */
    public string $vitalsSeverity;

    public function __construct(
        #[Input] public string $patientId,
        #[Input] public float $temperature,
        #[Input] public int $heartRate,
        #[Input] public int $bloodPressureSystolic,
        #[Input] public int $bloodPressureDiastolic,
        #[Input] public string $chiefComplaint,
        #[Input] public int $consciousnessLevel,
        #[Inject] VitalsAssessor $assessor,
    ) {
        $this->vitalsSeverity = $assessor->assess(
            $temperature,
            $heartRate,
            $bloodPressureSystolic,
            $bloodPressureDiastolic,
        );
    }
}
