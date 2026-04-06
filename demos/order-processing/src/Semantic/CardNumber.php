<?php

declare(strict_types=1);

namespace Be\App\Semantic;

use Be\Framework\Attribute\Validate;
use Be\App\Exception\InvalidCardNumberException;

/**
 * Card Number
 *
 * @link https://schema.org/accountId
 */
final class CardNumber
{
    #[Validate]
    public function validate(string $cardNumber): void
    {
        $cleaned = preg_replace('/\D/', '', $cardNumber) ?? '';

        if (strlen($cleaned) < 13 || strlen($cleaned) > 19) {
            throw new InvalidCardNumberException();
        }

        if (!$this->luhnCheck($cleaned)) {
            throw new InvalidCardNumberException();
        }
    }

    private function luhnCheck(string $number): bool
    {
        $digits = array_map('intval', array_reverse(str_split($number)));
        $sum = 0;

        foreach ($digits as $i => $digit) {
            $doubled = ($i % 2 === 1) ? $digit * 2 : $digit;
            $sum += ($doubled > 9) ? $doubled - 9 : $doubled;
        }

        return $sum % 10 === 0;
    }
}
