<?php

declare(strict_types=1);

namespace Be\App\Input;

use Be\App\Final\OrderConfirmed;
use Be\Framework\Attribute\Be;

/**
 * Order Input - Diamond Metamorphosis Demo
 *
 * @link https://schema.org/Order
 */
#[Be([OrderConfirmed::class])]
final readonly class OrderInput
{
    public function __construct(
        public string $cartId,
        public string $customerId,
        public string $cardNumber,
        public string $cardExpiry,
        public string $cardCvv,
        public string $postalCode,
        public string $streetAddress
    ) {
    }
}
