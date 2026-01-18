<?php

declare(strict_types=1);

namespace Be\App\Semantic;

use Be\Framework\Attribute\Validate;
use Be\App\Exception\InvalidPostalCodeException;

/**
 * Postal Code
 *
 * @link https://schema.org/postalCode
 */
final class PostalCode
{
    #[Validate]
    public function validate(string $postalCode): void
    {
        $cleaned = preg_replace('/[\s\-]/', '', $postalCode);

        // Japanese postal code: 7 digits
        if (!preg_match('/^\d{7}$/', $cleaned)) {
            throw new InvalidPostalCodeException();
        }
    }
}
