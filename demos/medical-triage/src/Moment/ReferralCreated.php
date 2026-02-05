<?php

declare(strict_types=1);

namespace Be\Demo\MedicalTriage\Moment;

use Be\Demo\MedicalTriage\Reason\ReferralPolicy;
use Ray\Di\Di\Inject;
use Ray\InputQuery\Attribute\Input;

/**
 * Referral Created - Pure data Moment (no Potential)
 *
 * Unlike BedAssigned and TeamAlerted, this Moment has no side effects.
 * It creates a referral to the appropriate outpatient department.
 * No MomentInterface - no be() needed.
 */
final readonly class ReferralCreated
{
    public string $referralId;
    public string $department;

    public function __construct(
        #[Input] public string $patientId,
        #[Input] public string $chiefComplaint,
        #[Inject] ReferralPolicy $policy,
    ) {
        $this->department = $policy->determineDepartment($chiefComplaint);
        $this->referralId = sprintf(
            'REF-%s-%s',
            date('Ymd'),
            substr(md5($patientId . $this->department . time()), 0, 8)
        );
    }
}
