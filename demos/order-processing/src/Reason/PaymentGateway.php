<?php

declare(strict_types=1);

namespace Be\App\Reason;

use Be\App\Moment\Potential\PaymentCapture;

/**
 * Payment Gateway - Reason (stateless gateway)
 *
 * Creates PaymentCapture moments that can be realized.
 */
final class PaymentGateway implements PaymentGatewayInterface
{
    public function authorize(string $cardNumber, int $amount): PaymentCapture
    {
        // Create authorization code (funds held, not charged)
        $authorizationCode = sprintf(
            'AUTH-%d-%s',
            $amount,
            substr(md5($cardNumber . time()), 0, 8)
        );

        // Return Moment with realize callback
        return new PaymentCapture(
            $authorizationCode,
            $amount,
            fn () => $this->capture($authorizationCode, $amount),
        );
    }

    private function capture(string $authorizationCode, int $amount): string
    {
        // External system call to capture payment
        // In production: return $this->api->capture($authorizationCode, $amount);
        return sprintf('TXN-%s-%s', date('YmdHis'), substr(md5($authorizationCode), 0, 6));
    }
}
