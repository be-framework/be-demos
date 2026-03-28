<?php

declare(strict_types=1);

namespace Be\Demo\ContactForm\Being;

use Be\Demo\ContactForm\Reason\EmailNormalizer;
use Ray\Di\Di\Inject;
use Ray\InputQuery\Attribute\Input;

/**
 * EmailNormalized - Being state
 *
 * Transforms the raw email into a normalized form.
 * Lowercases and removes +alias from the local part.
 */
final readonly class EmailNormalized
{
    public string $normalizedEmail;

    public function __construct(
        #[Input] string $email,
        #[Inject] EmailNormalizer $normalizer,
    ) {
        $this->normalizedEmail = $normalizer->normalize($email);
    }
}
