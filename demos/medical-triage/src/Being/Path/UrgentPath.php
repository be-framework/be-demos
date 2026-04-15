<?php

declare(strict_types=1);

namespace Be\Demo\MedicalTriage\Being\Path;

/**
 * Urgent Path - Discriminator marker for the urgent triage branch
 *
 * Used as the value of TriageLevelDetermined::$being when the JTAS protocol
 * classifies a patient as "urgent" (YELLOW). The Be Framework's type matcher
 * uses the runtime type of $being to select UrgentQueued as the Final.
 *
 * @see \Be\Demo\MedicalTriage\Being\TriageLevelDetermined
 * @see \Be\Demo\MedicalTriage\Final\UrgentQueued
 */
final readonly class UrgentPath
{
}
