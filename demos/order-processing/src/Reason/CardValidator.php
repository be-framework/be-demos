<?php

declare(strict_types=1);

namespace Be\App\Reason;

/**
 * Card Validator - Reason for card validation
 */
final class CardValidator
{
    public function validate(string $cardNumber, string $cardExpiry): bool
    {
        // Validate expiry format MM/YY
        if (!preg_match('/^(0[1-9]|1[0-2])\/(\d{2})$/', $cardExpiry, $matches)) {
            return false;
        }

        // Check card is not expired
        $expiry = \DateTimeImmutable::createFromFormat('!Y-m', sprintf('20%s-%s', $matches[2], $matches[1]));

        if ($expiry === false) {
            return false;
        }

        return $expiry >= new \DateTimeImmutable('first day of this month 00:00:00');
    }
}
