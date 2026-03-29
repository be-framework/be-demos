<?php

declare(strict_types=1);

namespace Be\Demo\MedicalTriage\Semantic;

use Be\Demo\MedicalTriage\Exception\InvalidPatientIdException;
use Be\Framework\Attribute\Validate;

/**
 * Patient ID
 *
 * Format: PT- followed by digits (e.g., PT-12345)
 *
 * @link https://schema.org/identifier
 */
final class PatientId
{
    #[Validate]
    public function validate(string $patientId): void
    {
        if (empty(trim($patientId))) {
            throw new InvalidPatientIdException();
        }

        // Format: PT-XXXXX (digits after prefix)
        if (!preg_match('/^PT-\d+$/', $patientId)) {
            throw new InvalidPatientIdException();
        }
    }
}
