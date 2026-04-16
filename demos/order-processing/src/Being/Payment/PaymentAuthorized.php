<?php

declare(strict_types=1);

namespace Be\Pattern\OrderProcessing\Being\Payment;

use Be\Pattern\OrderProcessing\Moment\Potential\PaymentCapture;
use Be\Pattern\OrderProcessing\Reason\PaymentGateway;
use Ray\Di\Di\Inject;
use Ray\InputQuery\Attribute\Input;

final readonly class PaymentAuthorized
{
    public PaymentCapture $capture;

    public function __construct(
        #[Input] public string $cardNumber,
        #[Input] public int $amount,
        #[Inject] PaymentGateway $gateway,
    ) {
        $this->capture = $gateway->authorize($cardNumber, $amount);
    }
}
