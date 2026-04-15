<?php

declare(strict_types=1);

namespace Be\Demo\MedicalTriage\Being\Path;

/**
 * Non-urgent Path - Discriminator marker for the outpatient triage branch
 *
 * Used as the value of TriageLevelDetermined::$being when the JTAS protocol
 * classifies a patient as "non-urgent" (GREEN). The Be Framework's type matcher
 * uses the runtime type of $being to select OutpatientReferred as the Final.
 *
 * @see \Be\Demo\MedicalTriage\Being\TriageLevelDetermined
 * @see \Be\Demo\MedicalTriage\Final\OutpatientReferred
 */
final readonly class NonUrgentPath
{
}
