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
        if ($amount < 1) {
            throw new InvalidAmountException();
        }

        // Maximum amount: 100 million yen
        if ($amount > 100000000) {
            throw new InvalidAmountException();
        }
    }
}
