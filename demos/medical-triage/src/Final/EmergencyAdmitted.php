<?php

declare(strict_types=1);

namespace Be\Demo\MedicalTriage\Final;

use Be\Demo\MedicalTriage\Being\Path\ImmediatePath;
use Be\Demo\MedicalTriage\Moment\BedAssigned;
use Be\Demo\MedicalTriage\Moment\TeamAlerted;
use Be\Demo\MedicalTriage\Reason\BedAllocatorInterface;
use Be\Demo\MedicalTriage\Reason\TeamDispatcherInterface;
use Ray\Di\Di\Inject;
use Ray\InputQuery\Attribute\Input;

/**
 * Emergency Admitted - Final (immediate triage path)
 *
 * Selected by the Be Framework when the preceding TriageLevelDetermined sets
 * $being to an {@see ImmediatePath}. Builds and realizes both Moments
 * (BedAssigned, TeamAlerted) through self-completion.
 *
 * @link https://schema.org/EmergencyService
 */
final readonly class EmergencyAdmitted
{
    public BedAssigned $bedAssigned;
    public TeamAlerted $teamAlerted;
    public string $admissionId;
    public string $status;

    public function __construct(
        #[Input] public ImmediatePath $being,
        #[Input] public string $patientId,
        #[Input] public string $triageCode,
        #[Inject] BedAllocatorInterface $bedAllocator,
        #[Inject] TeamDispatcherInterface $teamDispatcher,
    ) {
        // Born: create Moments (each carrying their Potential)
        $this->bedAssigned = new BedAssigned($patientId, $bedAllocator);
        $this->teamAlerted = new TeamAlerted($triageCode, $teamDispatcher);

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
