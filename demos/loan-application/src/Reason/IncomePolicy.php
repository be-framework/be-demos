<?php

declare(strict_types=1);

namespace Be\Pattern\LoanApplication\Reason;

/**
 * Income Policy - Reason (pure policy logic)
 *
 * Calculates debt-to-income ratio and assesses income stability.
 * Stateless policy object with no external dependencies.
 */
final class IncomePolicy
{
    private float $lastDti = 0.0;
    private string $lastStability = '';

    /**
     * Calculate debt-to-income ratio
     *
     * Monthly debt payment assumed as requestedAmount / 360 (30-year mortgage)
     */
    public function calculateDti(int $annualIncome, int $requestedAmount): float
    {
        $monthlyIncome = $annualIncome / 12;
        $monthlyPayment = $requestedAmount / 360; // 30-year amortization

        $this->lastDti = round($monthlyPayment / $monthlyIncome, 4);

        return $this->lastDti;
    }

    /**
     * Assess income stability based on employment years
     */
    public function assessStability(int $employmentYears): string
    {
        $this->lastStability = match (true) {
            $employmentYears >= 10 => 'excellent',
            $employmentYears >= 5 => 'good',
            $employmentYears >= 2 => 'fair',
            default => 'poor',
        };

        return $this->lastStability;
    }

    /**
     * Get the last computed assessment result for the IncomeApproved Moment
     *
     * @return array{dti: float, stability: string}
     */
    public function getAssessmentResult(): array
    {
        return [
            'dti' => $this->lastDti ?: 0.25,
            'stability' => $this->lastStability ?: 'good',
        ];
    }
}
