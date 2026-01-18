<?php

declare(strict_types=1);

namespace Be\App\Moment;

use Be\App\Attribute\Amount;
use Be\App\Attribute\CardNumber;
use Be\App\Moment\Potential\PaymentCapture;
use Be\App\Reason\PaymentGateway;
use Ray\Di\Di\Inject;

/**
 * Payment Completed - Moment (part + potential)
 *
 * Part of OrderConfirmed, holding the potential to capture payment.
 */
final readonly class PaymentCompleted implements MomentInterface
{
    public PaymentCapture $capture;

    public function __construct(
        #[CardNumber] public string $cardNumber,
        #[Amount] public int $amount,
        #[Inject] PaymentGateway $gateway,
    ) {
        // Born: create potential (authorize payment)
        $this->capture = $gateway->authorize($cardNumber, $amount);
    }

    public function be(): void
    {
        $this->capture->be();
    }
}
