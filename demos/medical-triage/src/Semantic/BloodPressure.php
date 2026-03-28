<?php

declare(strict_types=1);

namespace Be\Demo\MedicalTriage\Semantic;

use Be\Demo\MedicalTriage\Exception\InvalidBloodPressureException;
use Be\Framework\Attribute\Validate;

/**
 * Blood Pressure (mmHg)
 *
 * Systolic valid range: 40 - 300
 * Diastolic valid range: 20 - 200
 *
 * @link https://schema.org/bloodPressure
 */
final class BloodPressure
{
    #[Validate]
    public function validate(int $bloodPressureSystolic, int $bloodPressureDiastolic): void
    {
        if ($bloodPressureSystolic < 40 || $bloodPressureSystolic > 300) {
            throw new InvalidBloodPressureException('systolic_out_of_range');
        }

        if ($bloodPressureDiastolic < 20 || $bloodPressureDiastolic > 200) {
            throw new InvalidBloodPressureException('diastolic_out_of_range');
        }
    }
}
