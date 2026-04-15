<?php

declare(strict_types=1);

namespace Be\Pattern\InsuranceClaim\Semantic;

use Be\Framework\Attribute\Validate;
use Be\Pattern\InsuranceClaim\Exception\InvalidIncidentTypeException;

/**
 * Incident Type
 *
 * Allowed values: accident, theft, fire, natural_disaster, medical
 */
final class IncidentType
{
    private const ALLOWED = ['accident', 'theft', 'fire', 'natural_disaster', 'medical'];

    #[Validate]
    public function validate(string $incidentType): void
    {
        if (!in_array($incidentType, self::ALLOWED, true)) {
            throw new InvalidIncidentTypeException();
        }
    }
}
