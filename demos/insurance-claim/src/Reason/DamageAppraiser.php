<?php

declare(strict_types=1);

namespace Be\Pattern\InsuranceClaim\Reason;

use Be\Pattern\InsuranceClaim\Moment\Potential\DamageValuation;

/**
 * Damage Appraiser - Reason (stateless gateway)
 *
 * Appraises damage and creates DamageValuation moments that can be realized.
 */
final class DamageAppraiser implements DamageAppraiserInterface
{
    private int $lastAssessedAmount = 0;

    /**
     * @return array{assessedAmount: int, damageGrade: string}
     */
    public function appraise(string $incidentType, int $estimatedAmount, string $description): array
    {
        // Demo: apply adjustment factor based on incident type
        $factor = match ($incidentType) {
            'fire' => 0.95,
            'theft' => 0.80,
            'accident' => 0.90,
            'natural_disaster' => 1.00,
            'medical' => 0.85,
            default => 0.75,
        };

        $assessedAmount = (int) round($estimatedAmount * $factor);
        $this->lastAssessedAmount = $assessedAmount;

        $damageGrade = match (true) {
            $assessedAmount < 100_000 => 'minor',
            $assessedAmount < 500_000 => 'moderate',
            $assessedAmount < 2_000_000 => 'major',
            default => 'total',
        };

        return [
            'assessedAmount' => $assessedAmount,
            'damageGrade' => $damageGrade,
        ];
    }

    public function value(): DamageValuation
    {
        $amount = $this->lastAssessedAmount;
        $valuationRef = sprintf('DVAL-%s-%d', date('YmdHis'), random_int(1000, 9999));

        return new DamageValuation(
            $amount,
            fn () => $this->confirm($valuationRef),
        );
    }

    private function confirm(string $valuationRef): string
    {
        // External system call to confirm damage valuation
        // In production: $this->api->confirmValuation($valuationRef);
        return $valuationRef;
    }
}
