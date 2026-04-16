<?php

declare(strict_types=1);

namespace Be\Pattern\OrderProcessing\Reason;

use Be\Pattern\OrderProcessing\Moment\Potential\PaymentCapture;

/**
 * Payment Gateway Interface
 *
 * Enables testability through dependency injection.
 */
interface PaymentGatewayInterface
{
    public function authorize(string $cardNumber, int $amount): PaymentCapture;
}
