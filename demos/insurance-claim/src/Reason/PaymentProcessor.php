<?php

declare(strict_types=1);

namespace Be\Demo\InsuranceClaim\Reason;

use Be\Demo\InsuranceClaim\Moment\Potential\PaymentExecution;

/**
 * Payment Processor - Reason (stateless gateway)
 *
 * Creates PaymentExecution moments that can be realized.
 */
final class PaymentProcessor implements PaymentProcessorInterface
{
    private int $amount = 0;

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function authorize(): PaymentExecution
    {
        $amount = $this->amount;
        $authorizationCode = sprintf(
            'AUTH-%d-%s',
            $amount,
            substr(md5((string) $amount . (string) time()), 0, 8)
        );

        return new PaymentExecution(
            $authorizationCode,
            $amount,
            fn () => $this->execute($authorizationCode, $amount),
        );
    }

    private function execute(string $authorizationCode, int $amount): string
    {
        // External system call to execute payment
        // In production: return $this->api->execute($authorizationCode, $amount);
        return sprintf('TXN-%s-%d-%s', date('YmdHis'), $amount, substr(md5($authorizationCode), 0, 6));
    }
}
