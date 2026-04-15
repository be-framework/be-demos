<?php

declare(strict_types=1);

namespace Be\Demo\MedicalTriage\Input;

use Be\Demo\MedicalTriage\Being\TriageLevelDetermined;
use Be\Framework\Attribute\Be;

/**
 * Patient Input - Branching Metamorphosis Demo
 *
 * Entry point for the triage becoming chain:
 *
 *   PatientInput
 *     -> TriageLevelDetermined     (apply JTAS protocol, set $being discriminator)
 *     -> EmergencyAdmitted | UrgentQueued | OutpatientReferred   ($being type matching)
 *
 * Only three fields are collected from intake: the patient identifier, the
 * chief complaint in free text, and the Japan Coma Scale consciousness value.
 * That is the minimum information a triage nurse needs to reach a JTAS level,
 * and it keeps the demo focused on branching rather than on data plumbing.
 *
 * @link https://schema.org/Patient
 */
#[Be(TriageLevelDetermined::class)]
final readonly class PatientInput
{
    public function __construct(
        public string $patientId,
        public string $chiefComplaint,
        public int $consciousnessLevel,
    ) {
    }
}
