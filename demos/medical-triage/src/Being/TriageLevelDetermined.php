<?php

declare(strict_types=1);

namespace Be\Pattern\MedicalTriage\Being;

use Be\Pattern\MedicalTriage\Final\EmergencyAdmitted;
use Be\Pattern\MedicalTriage\Final\OutpatientReferred;
use Be\Pattern\MedicalTriage\Final\UrgentQueued;
use Be\Pattern\MedicalTriage\Reason\ImmediateCase;
use Be\Pattern\MedicalTriage\Reason\JTASProtocol;
use Be\Pattern\MedicalTriage\Reason\NonUrgentCase;
use Be\Pattern\MedicalTriage\Reason\UrgentCase;
use Be\Framework\Attribute\Be;
use Ray\Di\Di\Inject;
use Ray\InputQuery\Attribute\Input;
use UnexpectedValueException;

use function sprintf;

/**
 * Triage Level Determined - Branching Being
 *
 * Uses the JTAS Protocol (Policy Reason) to choose one of three Reason
 * strategies and assigns it to the typed $being discriminator. The Be
 * Framework then selects the matching Final by comparing that runtime type
 * against each Final's `#[Input] <Case> $being` parameter:
 *
 *   ImmediateCase  -> EmergencyAdmitted
 *   UrgentCase     -> UrgentQueued
 *   NonUrgentCase  -> OutpatientReferred
 *
 * The chosen Case is not just a marker - it carries the behavior the Final
 * will delegate to, following the FormalStyle/CasualStyle pattern from the
 * Be Framework's BeGreeting example.
 *
 * Final selection happens via {@see \Be\Framework\BecomingType::match()}.
 */
#[Be([EmergencyAdmitted::class, UrgentQueued::class, OutpatientReferred::class])]
final readonly class TriageLevelDetermined
{
    /** Typed discriminator used by the Be Framework to pick the next Final. */
    public ImmediateCase|UrgentCase|NonUrgentCase $being;

    public function __construct(
        #[Input] public string $patientId,
        #[Input] public string $chiefComplaint,
        #[Input] public int $consciousnessLevel,
        #[Inject] JTASProtocol $protocol,
    ) {
        $level = $protocol->assess($chiefComplaint, $consciousnessLevel);

        $this->being = match ($level) {
            'immediate' => new ImmediateCase(),
            'urgent' => new UrgentCase(),
            'non-urgent' => new NonUrgentCase(),
            default => throw new UnexpectedValueException(
                sprintf('Unsupported triage level: %s', $level),
            ),
        };
    }
}
