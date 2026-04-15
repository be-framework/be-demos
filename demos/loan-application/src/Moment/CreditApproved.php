<?php

declare(strict_types=1);

namespace Be\Pattern\LoanApplication\Moment;

use Be\Pattern\LoanApplication\Moment\Potential\CreditInquiry;
use Be\Pattern\LoanApplication\Reason\CreditBureauInterface;
use Ray\Di\Di\Inject;

/**
 * Credit Approved - Moment (part + potential)
 *
 * Part of EligibilityConfirmed (Stage 1), holding the potential to finalize credit inquiry.
 * Born from CreditBureau, carries CreditInquiry potential.
 */
final readonly class CreditApproved implements MomentInterface
{
    public CreditInquiry $inquiry;

    public function __construct(
        #[Inject] CreditBureauInterface $bureau,
    ) {
        // Born: create potential (initiate credit inquiry)
        $this->inquiry = $bureau->inquire();
    }

    public function be(): void
    {
        $this->inquiry->be();
    }
}
