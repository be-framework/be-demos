<?php

declare(strict_types=1);

namespace Be\Demo\MedicalTriage\Input;

use Be\Demo\MedicalTriage\Being\VitalsMeasured;
use Be\Framework\Attribute\Be;

/**
 * Patient Input - Branching Metamorphosis Demo
 *
 * Entry point for the triage becoming chain:
 *
 *   PatientInput
 *     -> VitalsMeasured            (assess vital signs)
 *     -> TriageLevelDetermined     (apply JTAS protocol, set $being discriminator)
 *     -> EmergencyAdmitted | UrgentQueued | OutpatientReferred   ($being type matching)
 *
 * @link https://schema.org/Patient
 */
#[Be(VitalsMeasured::class)]
final readonly class PatientInput
{
    public function __construct(
        public string $patientId,
        public float $temperature,
        public int $heartRate,
        public int $bloodPressureSystolic,
        public int $bloodPressureDiastolic,
        public string $chiefComplaint,
        public int $consciousnessLevel,
    ) {
    }
}
