<?php

declare(strict_types=1);

namespace Be\Pattern\OrderProcessing\Semantic;

use Be\Pattern\OrderProcessing\Exception\InvalidCardExpiryException;
use Be\Framework\Attribute\Validate;

/**
 * Card Expiry Date
 *
 * @link https://schema.org/validThrough
 */
final class CardExpiry
{
    #[Validate]
    public function validate(string $cardExpiry): void
    {
        // Format: MM/YY
        if (!preg_match('/^(0[1-9]|1[0-2])\/(\d{2})$/', $cardExpiry, $matches)) {
            throw new InvalidCardExpiryException();
        }

        // Valid through the last day of the expiry month: compare month to month
        $expiry = \DateTimeImmutable::createFromFormat('!Y-m', sprintf('20%s-%s', $matches[2], $matches[1]));

        if ($expiry < new \DateTimeImmutable('first day of this month 00:00:00')) {
            throw new InvalidCardExpiryException();
        }
    }
}
