<?php

declare(strict_types=1);

namespace Be\Demo\MedicalTriage\Final;

use Be\Demo\MedicalTriage\Moment\BedAssigned;
use Be\Demo\MedicalTriage\Moment\TeamAlerted;
use Ray\Di\Di\Inject;

/**
 * Emergency Admitted - Final (immediate triage path)
 *
 * Branching metamorphosis: PatientInput -> EmergencyAdmitted
 * Both Moments are realized here through self-completion.
 * BedAssigned and TeamAlerted each carry Potential that must be realized.
 *
 * @link https://schema.org/EmergencyService
 */
final readonly class EmergencyAdmitted
{
    public string $admissionId;
    public string $status;

    public function __construct(
        #[Inject] public BedAssigned $bedAssigned,
        #[Inject] public TeamAlerted $teamAlerted,
    ) {
        // Self-completion: realize all Moments (parts of self)
        $this->bedAssigned->be();
        $this->teamAlerted->be();

        $this->admissionId = $this->generateAdmissionId();
        $this->status = 'admitted';
    }

    private function generateAdmissionId(): string
    {
        return sprintf(
            'ADM-%s-%s',
            date('Ymd'),
            substr(md5(
                $this->bedAssigned->reservation->reservationId .
                $this->teamAlerted->alert->alertId
            ), 0, 8)
        );
    }
}
