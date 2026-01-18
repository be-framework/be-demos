<?php

declare(strict_types=1);

namespace Be\App\Semantic;

use Be\App\Exception\InvalidCardCvvException;
use Be\Framework\Attribute\Validate;

/**
 * Card CVV/CVC
 *
 * @link https://schema.org/accountId
 */
final class CardCvv
{
    #[Validate]
    public function validate(string $cardCvv): void
    {
        // CVV: 3 digits (Visa, MasterCard) or 4 digits (Amex)
        if (!preg_match('/^\d{3,4}$/', $cardCvv)) {
            throw new InvalidCardCvvException();
        }
    }
}
