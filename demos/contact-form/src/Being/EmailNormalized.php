<?php

declare(strict_types=1);

namespace Be\Demo\ContactForm\Being;

use Be\Demo\ContactForm\Final\ContactReceived;
use Be\Demo\ContactForm\Reason\EmailNormalizer;
use Be\Framework\Attribute\Be;
use Ray\Di\Di\Inject;
use Ray\InputQuery\Attribute\Input;

/**
 * EmailNormalized - Being state
 *
 * Transforms the raw email into a normalized form.
 * Lowercases and removes +alias from the local part.
 */
#[Be([ContactReceived::class])]
final readonly class EmailNormalized
{
    public string $normalizedEmail;

    public function __construct(
        #[Input] public string $name,
        #[Input] public string $email,
        #[Input] public string $subject,
        #[Input] public string $message,
        #[Inject] EmailNormalizer $normalizer,
    ) {
        $this->normalizedEmail = $normalizer->normalize($email);
    }
}
