<?php

declare(strict_types=1);

namespace Be\Demo\ContactForm\Semantic;

use Be\Demo\ContactForm\Exception\InvalidSubjectException;
use Be\Framework\Attribute\Validate;

/**
 * Subject - Semantic validation
 *
 * Validates that the subject is non-empty and within length limits.
 */
final class Subject
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
