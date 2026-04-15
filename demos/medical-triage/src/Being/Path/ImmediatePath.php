<?php

declare(strict_types=1);

namespace Be\Demo\MedicalTriage\Being\Path;

/**
 * Immediate Path - Discriminator marker for the emergency triage branch
 *
 * Used as the value of TriageLevelDetermined::$being when the JTAS protocol
 * classifies a patient as "immediate" (RED). The Be Framework's type matcher
 * uses the runtime type of $being to select EmergencyAdmitted as the Final.
 *
 * @see \Be\Demo\MedicalTriage\Being\TriageLevelDetermined
 * @see \Be\Demo\MedicalTriage\Final\EmergencyAdmitted
 */
final readonly class ImmediatePath
{
}
