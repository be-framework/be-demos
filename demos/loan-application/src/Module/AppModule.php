<?php

declare(strict_types=1);

namespace Be\Pattern\LoanApplication\Module;

use Be\Pattern\LoanApplication\Reason\CreditBureau;
use Be\Pattern\LoanApplication\Reason\CreditBureauInterface;
use Be\Pattern\LoanApplication\Reason\IdentityVerifier;
use Be\Pattern\LoanApplication\Reason\IdentityVerifierInterface;
use Be\Pattern\LoanApplication\Reason\IncomePolicy;
use Be\Pattern\LoanApplication\Reason\InsuranceQuoter;
use Be\Pattern\LoanApplication\Reason\InsuranceQuoterInterface;
use Be\Pattern\LoanApplication\Reason\LoanPolicy;
use Be\Pattern\LoanApplication\Reason\PropertyAppraisal;
use Be\Pattern\LoanApplication\Reason\PropertyAppraisalInterface;
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
