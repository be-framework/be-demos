<?php

declare(strict_types=1);

namespace Be\Demo\LoanApplication\Module;

use Be\Demo\LoanApplication\Reason\CreditBureau;
use Be\Demo\LoanApplication\Reason\CreditBureauInterface;
use Be\Demo\LoanApplication\Reason\IdentityVerifier;
use Be\Demo\LoanApplication\Reason\IdentityVerifierInterface;
use Be\Demo\LoanApplication\Reason\IncomePolicy;
use Be\Demo\LoanApplication\Reason\InsuranceQuoter;
use Be\Demo\LoanApplication\Reason\InsuranceQuoterInterface;
use Be\Demo\LoanApplication\Reason\LoanPolicy;
use Be\Demo\LoanApplication\Reason\PropertyAppraisal;
use Be\Demo\LoanApplication\Reason\PropertyAppraisalInterface;
use Ray\Di\AbstractModule;

final class AppModule extends AbstractModule
{
    protected function configure(): void
    {
        // Reason bindings (via interfaces for testability)
        $this->bind(IdentityVerifierInterface::class)->to(IdentityVerifier::class);
        $this->bind(CreditBureauInterface::class)->to(CreditBureau::class);
        $this->bind(IncomePolicy::class);
        $this->bind(PropertyAppraisalInterface::class)->to(PropertyAppraisal::class);
        $this->bind(InsuranceQuoterInterface::class)->to(InsuranceQuoter::class);
        $this->bind(LoanPolicy::class);
    }
}
