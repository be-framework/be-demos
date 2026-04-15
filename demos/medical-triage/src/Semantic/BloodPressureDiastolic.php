<?php

declare(strict_types=1);

namespace Be\Demo\MedicalTriage\Semantic;

use Be\Demo\MedicalTriage\Exception\InvalidBloodPressureException;
use Be\Framework\Attribute\Validate;

/**
 * Diastolic Blood Pressure (mmHg)
 *
 * Valid range: 20 - 200
 *
 * @link https://schema.org/bloodPressure
 */
final class BloodPressureDiastolic
{
    #[Validate]
    public function validate(int $bloodPressureDiastolic): void
    {
        if ($bloodPressureDiastolic < 20 || $bloodPressureDiastolic > 200) {
            throw new InvalidBloodPressureException('diastolic_out_of_range');
        }
    }
}
