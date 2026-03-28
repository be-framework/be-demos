<?php

declare(strict_types=1);

namespace Be\Demo\ContactForm\Reason;

/**
 * ReceiptGenerator - Reason class
 *
 * Provides the sufficient reason for receipt ID generation.
 * Generates a unique receipt ID in the format RCP-{date}-{hash}.
 */
final class ReceiptGenerator
{
    public function generate(): string
    {
        return sprintf(
            'RCP-%s-%s',
            date('Ymd'),
            substr(md5(uniqid((string) mt_rand(), true)), 0, 8)
        );
    }
}
