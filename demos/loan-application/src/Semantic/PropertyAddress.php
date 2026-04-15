<?php

declare(strict_types=1);

namespace Be\Pattern\LoanApplication\Semantic;

use Be\Pattern\LoanApplication\Exception\InvalidPropertyAddressException;
use Be\Framework\Attribute\Validate;

/**
 * Property Address
 *
 * @link https://schema.org/address
 */
final class PropertyAddress
{
    #[Validate]
    public function validate(string $propertyAddress): void
    {
        $trimmed = trim($propertyAddress);

        if (empty($trimmed)) {
            throw new InvalidPropertyAddressException('address_empty');
        }

        if (mb_strlen($trimmed) < 5) {
            throw new InvalidPropertyAddressException('address_too_short');
        }

        if (mb_strlen($trimmed) > 200) {
            throw new InvalidPropertyAddressException('address_too_long');
        }
    }
}
