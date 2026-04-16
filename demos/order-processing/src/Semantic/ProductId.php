<?php

declare(strict_types=1);

namespace Be\Pattern\OrderProcessing\Semantic;

use Be\Pattern\OrderProcessing\Exception\InvalidProductIdException;
use Be\Framework\Attribute\Validate;

/**
 * Product ID
 *
 * @link https://schema.org/productID
 */
final class ProductId
{
    #[Validate]
    public function validate(string $productId): void
    {
        if (empty(trim($productId))) {
            throw new InvalidProductIdException();
        }

        // Format: PROD-XXX (alphanumeric after prefix)
        if (!preg_match('/^PROD-[A-Za-z0-9]+$/', $productId)) {
            throw new InvalidProductIdException();
        }
    }
}
