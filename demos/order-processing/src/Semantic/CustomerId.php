<?php

declare(strict_types=1);

namespace Be\Pattern\OrderProcessing\Semantic;

use Be\Pattern\OrderProcessing\Exception\InvalidCustomerIdException;
use Be\Framework\Attribute\Validate;

/**
 * Customer ID
 *
 * @link https://schema.org/identifier
 */
final class CustomerId
{
    #[Validate]
    public function validate(string $customerId): void
    {
        if (empty(trim($customerId))) {
            throw new InvalidCustomerIdException();
        }

        // Format: CUST-XXX (alphanumeric after prefix)
        if (!preg_match('/^CUST-[A-Za-z0-9]+$/', $customerId)) {
            throw new InvalidCustomerIdException();
        }
    }
}
