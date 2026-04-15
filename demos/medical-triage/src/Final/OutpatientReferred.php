<?php

declare(strict_types=1);

namespace Be\Demo\MedicalTriage\Final;

use Be\Demo\MedicalTriage\Reason\NonUrgentCase;
use Ray\InputQuery\Attribute\Input;

/**
 * Outpatient Referred - Final (non-urgent triage path)
 *
 * Selected by the Be Framework when the preceding TriageLevelDetermined sets
 * its $being discriminator to a {@see NonUrgentCase}. The Final delegates
 * referral creation to that strategy via `$being->refer(...)`.
 *
 * @link https://schema.org/MedicalClinic
 */
final readonly class OutpatientReferred
{
    public string $referralId;
    public string $status;
    public string $triageCode;

    public function __construct(
        #[Input] public NonUrgentCase $being,
        #[Input] public string $patientId,
    ) {
        $result = $being->refer($patientId);
        $this->referralId = $result['referralId'];
        $this->status = $result['status'];
        $this->triageCode = $being->triageCode;
    }
}
