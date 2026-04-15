<?php

declare(strict_types=1);

namespace Be\Pattern\InsuranceClaim\Moment;

use Ray\Di\Di\Inject;
use Be\Pattern\InsuranceClaim\Reason\FraudDetectorInterface;

/**
 * Fraud Cleared - Moment (pure data, no Potential)
 *
 * Part of ClaimSettled/ClaimEscalated. Records fraud clearance outcome.
 * Pure data moment - does not implement MomentInterface as it has no potential to realize.
 */
final readonly class FraudCleared
{
    public string $clearanceId;
    public float $riskScore;

    public function __construct(
        #[Inject] FraudDetectorInterface $detector,
    ) {
        $result = $detector->clear();
        $this->clearanceId = $result['clearanceId'];
        $this->riskScore = $result['riskScore'];
    }
}
