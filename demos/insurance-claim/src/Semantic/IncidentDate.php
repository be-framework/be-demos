<?php

declare(strict_types=1);

namespace Be\Demo\InsuranceClaim\Semantic;

use Be\Framework\Attribute\Validate;
use Be\Demo\InsuranceClaim\Exception\InvalidIncidentDateException;

/**
 * Incident Date
 *
 * Format: Y-m-d, must be in the past.
 */
final class IncidentDate
{
    #[Validate]
    public function validate(string $incidentDate): void
    {
        $date = \DateTimeImmutable::createFromFormat('Y-m-d', $incidentDate);

        if ($date === false || $date->format('Y-m-d') !== $incidentDate) {
            throw new InvalidIncidentDateException();
        }

        if ($date >= new \DateTimeImmutable('today')) {
            throw new InvalidIncidentDateException();
        }
    }
}
