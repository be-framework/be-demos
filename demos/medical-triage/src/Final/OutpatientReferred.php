<?php

declare(strict_types=1);

namespace Be\Demo\MedicalTriage\Final;

use Be\Demo\MedicalTriage\Being\Path\NonUrgentPath;
use Be\Demo\MedicalTriage\Moment\ReferralCreated;
use Be\Demo\MedicalTriage\Reason\ReferralPolicy;
use Ray\Di\Di\Inject;
use Ray\InputQuery\Attribute\Input;

/**
 * Outpatient Referred - Final (non-urgent triage path)
 *
 * Selected by the Be Framework when the preceding TriageLevelDetermined sets
 * $being to a {@see NonUrgentPath}. ReferralCreated is a pure data Moment with
 * no Potential - no be() call needed, just data assembly.
 *
 * @link https://schema.org/MedicalClinic
 */
final readonly class OutpatientReferred
{
    public ReferralCreated $referralCreated;
    public string $referralNumber;
    public string $status;

    public function __construct(
        #[Input] public NonUrgentPath $being,
        #[Input] public string $patientId,
        #[Input] public string $chiefComplaint,
        #[Inject] ReferralPolicy $policy,
    ) {
        $this->referralCreated = new ReferralCreated($patientId, $chiefComplaint, $policy);
        $this->referralNumber = $this->referralCreated->referralId;
        $this->status = 'referred';
    }
}
