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
        $cleaned = preg_replace('/\D/', '', $cardNumber);

        if (strlen($cleaned) < 13 || strlen($cleaned) > 19) {
            throw new InvalidCardNumberException();
        }

        if (!$this->luhnCheck($cleaned)) {
            throw new InvalidCardNumberException();
        }
    }

    private function luhnCheck(string $number): bool
    {
        $sum = 0;
        $length = strlen($number);
        for ($i = 0; $i < $length; $i++) {
            $digit = (int) $number[$length - 1 - $i];
            if ($i % 2 === 1) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            $sum += $digit;
        }
        return $sum % 10 === 0;
    }
}
