<?php

declare(strict_types=1);

namespace Be\Demo\MedicalTriage\Semantic;

use Be\Demo\MedicalTriage\Exception\InvalidBloodPressureException;
use Be\Framework\Attribute\Validate;

/**
 * Systolic Blood Pressure (mmHg)
 *
 * Valid range: 40 - 300
 *
 * @link https://schema.org/bloodPressure
 */
final class BloodPressureSystolic
{
    #[Validate]
    public function validate(int $bloodPressureSystolic): void
    {
        if ($bloodPressureSystolic < 40 || $bloodPressureSystolic > 300) {
            throw new InvalidBloodPressureException('systolic_out_of_range');
        }
    }
}
