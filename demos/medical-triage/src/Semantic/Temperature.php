<?php

declare(strict_types=1);

namespace Be\Demo\MedicalTriage\Semantic;

use Be\Demo\MedicalTriage\Exception\InvalidTemperatureException;
use Be\Framework\Attribute\Validate;

/**
 * Body Temperature (Celsius)
 *
 * Valid range: 30.0 - 45.0
 *
 * @link https://schema.org/bodyTemperature
 */
final class Temperature
{
    #[Validate]
    public function validate(float $temperature): void
    {
        if ($temperature < 30.0 || $temperature > 45.0) {
            throw new InvalidTemperatureException();
        }
    }
}
