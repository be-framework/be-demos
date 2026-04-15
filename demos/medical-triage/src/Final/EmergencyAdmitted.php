<?php

declare(strict_types=1);

namespace Be\Pattern\MedicalTriage\Final;

use Be\Pattern\MedicalTriage\Reason\ImmediateCase;
use Ray\InputQuery\Attribute\Input;

/**
 * Emergency Admitted - Final (immediate triage path)
 *
 * Selected by the Be Framework when the preceding TriageLevelDetermined sets
 * its $being discriminator to an {@see ImmediateCase}. The Final delegates all
 * domain work to that strategy via `$being->admit(...)`, mirroring the
 * FormalStyle/CasualStyle pattern from the BeGreeting example.
 *
 * @link https://schema.org/EmergencyService
 */
final readonly class EmergencyAdmitted
{
    public string $admissionId;
    public string $status;
    public string $triageCode;

    public function __construct(
        #[Input] public ImmediateCase $being,
        #[Input] public string $patientId,
    ) {
        $result = $being->admit($patientId);
        $this->admissionId = $result['admissionId'];
        $this->status = $result['status'];
        $this->triageCode = $being->triageCode;
    }
}
