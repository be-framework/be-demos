<?php

declare(strict_types=1);

namespace Be\App\Semantic;

use Be\App\Exception\InvalidAmountException;
use Be\Framework\Attribute\Validate;

/**
 * Payment Amount
 *
 * @link https://schema.org/price
 */
final class Amount
{
    #[Validate]
    public function validate(int $amount): void
    {
        // Valid range: 1 - 100 million yen
        if ($amount < 1 || $amount > 100_000_000) {
            throw new InvalidAmountException();
        }
    }
}
