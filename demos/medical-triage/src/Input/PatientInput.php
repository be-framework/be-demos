<?php

declare(strict_types=1);

namespace Be\Demo\MedicalTriage\Input;

use Be\Demo\MedicalTriage\Final\EmergencyAdmitted;
use Be\Demo\MedicalTriage\Final\OutpatientReferred;
use Be\Demo\MedicalTriage\Final\UrgentQueued;
use Be\Framework\Attribute\Be;

/**
 * Patient Input - Branching Metamorphosis Demo
 *
 * One Input with MULTIPLE possible Finals.
 * The JTASProtocol Reason determines which Final path is taken.
 *
 * @link https://schema.org/Patient
 */
#[Be([EmergencyAdmitted::class, UrgentQueued::class, OutpatientReferred::class])]
final readonly class PatientInput
{
    public function __construct(
        public string $patientId,
        public float $temperature,
        public int $heartRate,
        public int $bloodPressureSystolic,
        public int $bloodPressureDiastolic,
        public string $chiefComplaint,
        public int $consciousnessLevel
    ) {
    }
}
