<?php

declare(strict_types=1);

namespace Be\Demo\MedicalTriage\Final;

use Be\Demo\MedicalTriage\Moment\ReferralCreated;
use Ray\Di\Di\Inject;

/**
 * Outpatient Referred - Final (non-urgent triage path)
 *
 * Branching metamorphosis: PatientInput -> OutpatientReferred
 * ReferralCreated is a pure data Moment with no Potential.
 * No be() call needed - just data assembly.
 *
 * @link https://schema.org/MedicalClinic
 */
final readonly class OutpatientReferred
{
    public string $referralNumber;
    public string $status;

    public function __construct(
        #[Inject] public ReferralCreated $referralCreated,
    ) {
        // No be() - ReferralCreated has no Potential to realize
        $this->referralNumber = $this->referralCreated->referralId;
        $this->status = 'referred';
    }
}
