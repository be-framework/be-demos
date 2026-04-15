<?php

declare(strict_types=1);

namespace Be\Pattern\LoanApplication\Semantic;

use Be\Pattern\LoanApplication\Exception\InvalidEmploymentException;
use Be\Framework\Attribute\Validate;

/**
 * Employment Years
 *
 * @link https://schema.org/yearsEmployed
 */
final class EmploymentYears
{
    #[Validate]
    public function validate(int $employmentYears): void
    {
        if ($employmentYears < 0) {
            throw new InvalidEmploymentException('years_negative');
        }

        if ($employmentYears > 50) {
            throw new InvalidEmploymentException('years_too_high');
        }
    }
}
