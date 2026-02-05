<?php

declare(strict_types=1);

namespace Be\Demo\ContactForm\Semantic;

use Be\Demo\ContactForm\Exception\InvalidSubjectException;
use Be\Framework\Attribute\Validate;

/**
 * SubjectLine - Semantic validation
 *
 * Validates that the subject line is non-empty and within length limits.
 */
final class SubjectLine
{
    #[Validate]
    public function validate(string $subject): void
    {
        if (empty(trim($subject))) {
            throw new InvalidSubjectException();
        }

        if (mb_strlen($subject) > 200) {
            throw new InvalidSubjectException();
        }
    }
}
