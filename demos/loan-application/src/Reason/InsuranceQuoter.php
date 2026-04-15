<?php

declare(strict_types=1);

namespace Be\Pattern\LoanApplication\Reason;

use Be\Pattern\LoanApplication\Moment\Potential\InsuranceContract;

/**
 * Insurance Quoter - Reason (stateless gateway)
 *
 * Quotes insurance premiums and creates InsuranceContract moments that can be realized.
 */
final class InsuranceQuoter implements InsuranceQuoterInterface
{
    private int $lastPremium = 0;
    private int $lastCoverage = 0;

    /** @return array{monthlyPremium: int, coverageAmount: int} */
    public function quote(string $applicantId, int $requestedAmount): array
    {
        // Demo: premium is 0.3% of loan amount per month
        $this->lastPremium = (int) round($requestedAmount * 0.003);
        // Coverage equals the requested loan amount
        $this->lastCoverage = $requestedAmount;

        return [
            'monthlyPremium' => $this->lastPremium,
            'coverageAmount' => $this->lastCoverage,
        ];
    }

    public function prepare(): InsuranceContract
    {
        $premium = $this->lastPremium ?: 15000;
        $coverage = $this->lastCoverage ?: 50000000;

        return new InsuranceContract(
            $premium,
            $coverage,
            fn () => $this->bindContract($premium, $coverage),
        );
    }

    private function bindContract(int $premium, int $coverage): string
    {
        // External system call to bind insurance contract
        // In production: return $this->api->bindPolicy($premium, $coverage);
        return sprintf('INS-%s-%s', date('YmdHis'), substr(md5($premium . $coverage), 0, 8));
    }
}
