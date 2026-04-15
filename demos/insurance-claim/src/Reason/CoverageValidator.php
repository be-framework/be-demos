<?php

declare(strict_types=1);

namespace Be\Pattern\InsuranceClaim\Reason;

/**
 * Coverage Validator - Reason for coverage applicability check
 *
 * Validates that the policy coverage type applies to the incident type.
 */
final class CoverageValidator
{
    private const COVERAGE_MAP = [
        'comprehensive' => ['accident', 'theft', 'fire', 'natural_disaster', 'medical'],
        'liability' => ['accident'],
        'medical' => ['medical', 'accident'],
        'property' => ['fire', 'natural_disaster', 'theft'],
    ];

    /**
     * @return array{applicable: bool, validationId: string}
     */
    public function validate(string $incidentType, string $coverageType): array
    {
        $applicable = in_array(
            $incidentType,
            self::COVERAGE_MAP[$coverageType] ?? [],
            true
        );

        return [
            'applicable' => $applicable,
            'validationId' => sprintf(
                'VAL-%s-%s',
                date('YmdHis'),
                substr(md5($incidentType . $coverageType), 0, 6)
            ),
        ];
    }
}
