<?php

declare(strict_types=1);

namespace Be\Demo\InsuranceClaim\Reason;

use Be\Demo\InsuranceClaim\Moment\Potential\DamageValuation;

/**
 * Damage Appraiser Interface
 *
 * Enables testability through dependency injection.
 */
interface DamageAppraiserInterface
{
    /**
     * @return array{assessedAmount: int, damageGrade: string}
     */
    public function appraise(string $incidentType, int $estimatedAmount, string $description): array;

    public function value(): DamageValuation;
}
