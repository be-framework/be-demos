<?php

declare(strict_types=1);

namespace Be\Demo\InsuranceClaim\Reason;

/**
 * Fraud Detector - Reason (stateless gateway)
 *
 * Screens claims for fraud indicators and issues clearance.
 */
final class FraudDetector implements FraudDetectorInterface
{
    private float $lastRiskScore = 0.0;

    /**
     * @return array{riskScore: float, flagged: bool}
     */
    public function screen(string $claimantId, int $estimatedAmount, string $description): array
    {
        // Demo: simple risk scoring based on amount
        $riskScore = min(1.0, $estimatedAmount / 10_000_000);
        $this->lastRiskScore = $riskScore;

        return [
            'riskScore' => round($riskScore, 2),
            'flagged' => $riskScore > 0.7,
        ];
    }

    /**
     * @return array{clearanceId: string, riskScore: float}
     */
    public function clear(): array
    {
        return [
            'clearanceId' => sprintf('FRC-%s-%d', date('YmdHis'), random_int(1000, 9999)),
            'riskScore' => $this->lastRiskScore,
        ];
    }
}
