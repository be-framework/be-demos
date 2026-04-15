<?php

declare(strict_types=1);

namespace Be\Pattern\LoanApplication\Semantic;

use Be\Pattern\LoanApplication\Exception\InvalidApplicantIdException;
use Be\Framework\Attribute\Validate;

/**
 * Applicant ID
 *
 * @link https://schema.org/identifier
 */
final class ApplicantId
{
    #[Validate]
    public function validate(string $applicantId): void
    {
        if (empty(trim($applicantId))) {
            throw new InvalidApplicantIdException('id_empty');
        }

        // Format: APP-XXX (alphanumeric after prefix)
        if (!preg_match('/^APP-[A-Za-z0-9]+$/', $applicantId)) {
            throw new InvalidApplicantIdException('id_invalid_format');
        }
    }
}
