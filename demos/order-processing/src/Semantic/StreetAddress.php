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
        $length = mb_strlen(trim($streetAddress));

        if ($length < 5 || $length > 200) {
            throw new InvalidStreetAddressException();
        }
    }
}
