<?php

declare(strict_types=1);

namespace Be\Pattern\OrderProcessing\Moment;

use Be\Pattern\OrderProcessing\Attribute\Amount;
use Be\Pattern\OrderProcessing\Attribute\CardNumber;
use Be\Pattern\OrderProcessing\Moment\Potential\PaymentCapture;
use Be\Pattern\OrderProcessing\Reason\PaymentGatewayInterface;
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
        #[Inject] PaymentGatewayInterface $gateway,
    ) {
        // Born: create potential (authorize payment)
        $this->capture = $gateway->authorize($cardNumber, $amount);
    }

    public function be(): void
    {
        $this->capture->be();
    }
}
