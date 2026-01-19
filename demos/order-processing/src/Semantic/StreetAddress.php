<?php

declare(strict_types=1);

namespace Be\App\Semantic;

use Be\App\Exception\InvalidStreetAddressException;
use Be\Framework\Attribute\Validate;

/**
 * Street Address
 *
 * @link https://schema.org/streetAddress
 */
final class StreetAddress
{
    #[Validate]
    public function validate(string $streetAddress): void
    {
        $trimmed = trim($streetAddress);

        if (empty($trimmed)) {
            throw new InvalidStreetAddressException();
        }

        if (mb_strlen($trimmed) < 5) {
            throw new InvalidStreetAddressException();
        }

        if (mb_strlen($trimmed) > 200) {
            throw new InvalidStreetAddressException();
        }
    }
}
