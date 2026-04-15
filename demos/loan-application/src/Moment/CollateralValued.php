<?php

declare(strict_types=1);

namespace Be\Pattern\LoanApplication\Moment;

use Be\Pattern\LoanApplication\Moment\Potential\CollateralRegistration;
use Be\Pattern\LoanApplication\Reason\PropertyAppraisalInterface;
use Ray\Di\Di\Inject;

/**
 * Collateral Valued - Moment (part + potential)
 *
 * Part of LoanApproved (Stage 2), holding the potential to register collateral.
 * Born from PropertyAppraisal, carries CollateralRegistration potential.
 */
final readonly class CollateralValued implements MomentInterface
{
    public CollateralRegistration $registration;

    public function __construct(
        #[Inject] PropertyAppraisalInterface $appraiser,
    ) {
        // Born: create potential (prepare collateral registration)
        $this->registration = $appraiser->register();
    }

    public function be(): void
    {
        $this->registration->be();
    }
}
