<?php

declare(strict_types=1);

namespace Be\Demo\MedicalTriage\Semantic;

use Be\Demo\MedicalTriage\Exception\InvalidHeartRateException;
use Be\Framework\Attribute\Validate;

/**
 * Heart Rate (beats per minute)
 *
 * Valid range: 0 - 300
 *
 * @link https://schema.org/physiologicalMeasurement
 */
final class HeartRate
{
    #[Validate]
    public function validate(int $heartRate): void
    {
        if ($heartRate < 0 || $heartRate > 300) {
            throw new InvalidHeartRateException();
        }
    }
}
