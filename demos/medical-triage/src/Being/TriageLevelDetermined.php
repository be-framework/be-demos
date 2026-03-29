<?php

declare(strict_types=1);

namespace Be\Demo\MedicalTriage\Being;

use Be\Demo\MedicalTriage\Reason\JTASProtocol;
use Ray\Di\Di\Inject;
use Ray\InputQuery\Attribute\Input;

/**
 * Triage Level Determined - Being (intermediate state)
 *
 * Uses the JTAS Protocol (Policy Reason) to determine triage level.
 * The triage level determines which Final path the patient takes:
 *   immediate  -> EmergencyAdmitted
 *   urgent     -> UrgentQueued
 *   non-urgent -> OutpatientReferred
 */
final readonly class TriageLevelDetermined
{
    /** @var string 'immediate'|'urgent'|'non-urgent' */
    public string $triageLevel;

    /** @var string 'RED'|'YELLOW'|'GREEN' */
    public string $triageCode;

    public function __construct(
        #[Input] public string $chiefComplaint,
        #[Input] public int $consciousnessLevel,
        #[Input] public float $temperature,
        #[Input] public int $heartRate,
        #[Input] public int $bloodPressureSystolic,
        #[Inject] JTASProtocol $protocol
    ) {
        $result = $protocol->assess(
            $chiefComplaint,
            $consciousnessLevel,
            $temperature,
            $heartRate,
            $bloodPressureSystolic
        );

        $this->triageLevel = $result['level'];
        $this->triageCode = $result['code'];
    }
}
