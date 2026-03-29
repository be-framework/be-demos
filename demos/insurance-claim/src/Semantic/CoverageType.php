<?php

declare(strict_types=1);

namespace Be\Demo\InsuranceClaim\Semantic;

use Be\Framework\Attribute\Validate;
use Be\Demo\InsuranceClaim\Exception\InvalidCoverageTypeException;

/**
 * Coverage Type
 *
 * Allowed values: comprehensive, liability, medical, property
 */
final class CoverageType
{
    private const ALLOWED = ['comprehensive', 'liability', 'medical', 'property'];

    #[Validate]
    public function validate(string $coverageType): void
    {
        if (!in_array($coverageType, self::ALLOWED, true)) {
            throw new InvalidCoverageTypeException();
        }
    }
}
