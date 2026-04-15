<?php

declare(strict_types=1);

namespace Be\Pattern\InsuranceClaim\Being;

use Ray\InputQuery\Attribute\Input;
use Ray\Di\Di\Inject;
use Be\Pattern\InsuranceClaim\Reason\FraudDetectorInterface;

/**
 * Fraud Screened - Being (3-way parallel, branch 3)
 *
 * Screens the claim for potential fraud indicators.
 */
final readonly class FraudScreened
{
    public float $riskScore;
    public bool $flagged;

    public function __construct(
        #[Input] public string $claimantId,
        #[Input] public int $estimatedAmount,
        #[Input] public string $description,
        #[Inject] FraudDetectorInterface $detector
    ) {
        $result = $detector->screen($claimantId, $estimatedAmount, $description);
        $this->riskScore = $result['riskScore'];
        $this->flagged = $result['flagged'];
    }
}
