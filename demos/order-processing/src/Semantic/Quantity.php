<?php

declare(strict_types=1);

namespace Be\App\Semantic;

use Be\Framework\Attribute\Validate;
use Be\App\Exception\InvalidQuantityException;

/**
 * Quantity
 *
 * @link https://schema.org/quantity
 */
final class Quantity
{
    #[Validate]
    public function validate(int $quantity): void
    {
        if ($quantity < 1) {
            throw new InvalidQuantityException('quantity_too_low');
        }

        if ($quantity > 99) {
            throw new InvalidQuantityException('quantity_too_high');
        }
    }
}
