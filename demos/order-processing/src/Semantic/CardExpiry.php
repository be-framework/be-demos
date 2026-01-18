<?php

declare(strict_types=1);

namespace Be\App\Semantic;

use Be\App\Exception\InvalidCardExpiryException;
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
        if (!preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $cardExpiry)) {
            throw new InvalidCardExpiryException();
        }

        // Check if not expired
        [$month, $year] = explode('/', $cardExpiry);
        $expiryDate = \DateTime::createFromFormat('m/y', $cardExpiry);
        $expiryDate->modify('last day of this month');

        if ($expiryDate < new \DateTime()) {
            throw new InvalidCardExpiryException();
        }
    }
}
