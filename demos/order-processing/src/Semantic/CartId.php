<?php

declare(strict_types=1);

namespace Be\App\Semantic;

use Be\App\Exception\InvalidCartIdException;
use Be\Framework\Attribute\Validate;

/**
 * Cart ID
 *
 * @link https://schema.org/identifier
 */
final class CartId
{
    #[Validate]
    public function validate(string $cartId): void
    {
        if (empty(trim($cartId))) {
            throw new InvalidCartIdException();
        }

        // Format: CART-XXX (alphanumeric after prefix)
        if (!preg_match('/^CART-[A-Za-z0-9]+$/', $cartId)) {
            throw new InvalidCartIdException();
        }
    }
}
