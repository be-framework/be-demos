<?php

declare(strict_types=1);

namespace Be\Demo\MedicalTriage\Being;

use Be\Demo\MedicalTriage\Being\Path\ImmediatePath;
use Be\Demo\MedicalTriage\Being\Path\NonUrgentPath;
use Be\Demo\MedicalTriage\Being\Path\UrgentPath;
use Be\Demo\MedicalTriage\Final\EmergencyAdmitted;
use Be\Demo\MedicalTriage\Final\OutpatientReferred;
use Be\Demo\MedicalTriage\Final\UrgentQueued;
use Be\Demo\MedicalTriage\Reason\JTASProtocol;
use Be\Framework\Attribute\Be;
use Ray\Di\Di\Inject;
use Ray\InputQuery\Attribute\Input;
use UnexpectedValueException;

use function sprintf;

/**
 * Triage Level Determined - Branching Being
 *
 * Uses the JTAS Protocol (Policy Reason) to determine triage level, then sets
 * a typed $being discriminator that the Be Framework uses to select the Final:
 *
 *   immediate  -> ImmediatePath  -> EmergencyAdmitted
 *   urgent     -> UrgentPath     -> UrgentQueued
 *   non-urgent -> NonUrgentPath  -> OutpatientReferred
 *
 * Final selection happens via {@see \Be\Framework\BecomingType::match()} matching
 * the runtime type of $being against each Final's `#[Input] ...Path $being`
 * constructor parameter.
 */
#[Be([EmergencyAdmitted::class, UrgentQueued::class, OutpatientReferred::class])]
final readonly class TriageLevelDetermined
{
    /** @var string 'immediate'|'urgent'|'non-urgent' */
    public string $triageLevel;

    /** @var string 'RED'|'YELLOW'|'GREEN' */
    public string $triageCode;

    /** Typed discriminator used by the Be Framework to pick the next Final. */
    public ImmediatePath|UrgentPath|NonUrgentPath $being;

    public function __construct(
        #[Input] public string $patientId,
        #[Input] public float $temperature,
        #[Input] public int $heartRate,
        #[Input] public int $bloodPressureSystolic,
        #[Input] public int $bloodPressureDiastolic,
        #[Input] public string $chiefComplaint,
        #[Input] public int $consciousnessLevel,
        #[Input] public string $vitalsSeverity,
        #[Inject] JTASProtocol $protocol,
    ) {
        $result = $protocol->assess(
            $chiefComplaint,
            $consciousnessLevel,
            $temperature,
            $heartRate,
            $bloodPressureSystolic,
        );

        $this->triageLevel = $result['level'];
        $this->triageCode = $result['code'];

        $this->being = match ($this->triageLevel) {
            'immediate' => new ImmediatePath(),
            'urgent' => new UrgentPath(),
            'non-urgent' => new NonUrgentPath(),
            default => throw new UnexpectedValueException(
                sprintf('Unsupported triage level: %s', $this->triageLevel),
            ),
        };
    }
}
