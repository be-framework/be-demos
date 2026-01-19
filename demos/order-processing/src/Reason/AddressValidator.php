<?php

declare(strict_types=1);

namespace Be\App\Reason;

/**
 * Address Validator - Reason for address validation
 */
final class AddressValidator
{
    public function validate(string $postalCode, string $streetAddress): bool
    {
        return !empty(trim($postalCode)) && !empty(trim($streetAddress));
    }

    public function normalize(string $postalCode, string $streetAddress): string
    {
        $cleaned = preg_replace('/[\s\-]/', '', $postalCode);
        return sprintf('〒%s-%s %s',
            substr($cleaned, 0, 3),
            substr($cleaned, 3),
            trim($streetAddress)
        );
    }
}
