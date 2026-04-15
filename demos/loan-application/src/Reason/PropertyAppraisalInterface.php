<?php

declare(strict_types=1);

namespace Be\Pattern\LoanApplication\Reason;

use Be\Pattern\LoanApplication\Moment\Potential\CollateralRegistration;

/**
 * Property Appraisal Interface
 *
 * Enables testability through dependency injection.
 */
interface PropertyAppraisalInterface
{
    /**
     * Appraise the property value
     */
    public function appraise(string $propertyAddress, string $propertyType): int;

    /**
     * Create a collateral registration Moment with potential to register
     */
    public function register(): CollateralRegistration;
}
