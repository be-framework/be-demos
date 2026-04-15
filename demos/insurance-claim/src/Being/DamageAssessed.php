<?php

declare(strict_types=1);

namespace Be\Pattern\InsuranceClaim\Being;

use Ray\InputQuery\Attribute\Input;
use Ray\Di\Di\Inject;
use Be\Pattern\InsuranceClaim\Reason\DamageAppraiserInterface;

/**
 * Damage Assessed - Being (3-way parallel, branch 1)
 *
 * Appraises the damage and produces an assessed amount and grade.
 */
final readonly class DamageAssessed
{
    public int $assessedAmount;
    public string $damageGrade;

    public function __construct(
        #[Input] public string $incidentType,
        #[Input] public int $estimatedAmount,
        #[Input] public string $description,
        #[Inject] DamageAppraiserInterface $appraiser
    ) {
        $result = $appraiser->appraise($incidentType, $estimatedAmount, $description);
        $this->assessedAmount = $result['assessedAmount'];
        $this->damageGrade = $result['damageGrade'];
    }
}
