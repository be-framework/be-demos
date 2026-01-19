<?php

declare(strict_types=1);

namespace Be\App\Reason;

use Be\App\Moment\Potential\PaymentCapture;

/**
 * Payment Gateway Interface
 *
 * Enables testability through dependency injection.
 */
interface PaymentGatewayInterface
{
    public function authorize(string $cardNumber, int $amount): PaymentCapture;
}
