<?php

declare(strict_types=1);

namespace Be\Pattern\InsuranceClaim\Reason;

/**
 * Fraud Detector Interface
 *
 * Enables testability through dependency injection.
 */
interface FraudDetectorInterface
{
    /**
     * @return array{riskScore: float, flagged: bool}
     */
    public function screen(string $claimantId, int $estimatedAmount, string $description): array;

    /**
     * @return array{clearanceId: string, riskScore: float}
     */
    public function clear(): array;
}
