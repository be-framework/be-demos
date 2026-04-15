<?php

declare(strict_types=1);

namespace Be\Pattern\LoanApplication\Reason;

/**
 * Loan Policy - Reason (pure policy logic)
 *
 * Calculates approved loan amount based on LTV (Loan-to-Value) ratio
 * and determines the interest rate.
 */
final class LoanPolicy
{
    private const float MAX_LTV_RATIO = 0.80;
    private const float BASE_INTEREST_RATE = 1.5;

    /**
     * Calculate approved amount based on LTV ratio
     *
     * The approved amount is the lesser of the requested amount
     * and the maximum amount based on the appraised value.
     */
    public function calculateApprovedAmount(int $requestedAmount, int $appraisedValue): int
    {
        $maxLoanAmount = (int) floor($appraisedValue * self::MAX_LTV_RATIO);

        return min($requestedAmount, $maxLoanAmount);
    }

    /**
     * Calculate interest rate based on LTV ratio
     *
     * Higher LTV = higher risk = higher interest rate
     */
    public function calculateInterestRate(int $requestedAmount, int $appraisedValue): float
    {
        $ltv = $requestedAmount / $appraisedValue;

        $riskPremium = match (true) {
            $ltv <= 0.50 => 0.0,
            $ltv <= 0.60 => 0.25,
            $ltv <= 0.70 => 0.50,
            $ltv <= 0.80 => 0.75,
            default => 1.25,
        };

        return round(self::BASE_INTEREST_RATE + $riskPremium, 2);
    }
}
