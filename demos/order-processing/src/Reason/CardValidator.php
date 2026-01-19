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
        // Demo: validate expiry format MM/YY and not expired
        if (!preg_match('/^(0[1-9]|1[0-2])\/(\d{2})$/', $cardExpiry, $matches)) {
            return false;
        }

        $month = (int) $matches[1];
        $year = 2000 + (int) $matches[2];
        $now = new \DateTimeImmutable();

        return $year > (int) $now->format('Y')
            || ($year === (int) $now->format('Y') && $month >= (int) $now->format('m'));
    }
}
