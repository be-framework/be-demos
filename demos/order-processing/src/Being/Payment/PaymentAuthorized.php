<?php

declare(strict_types=1);

namespace Be\App\Being\Payment;

use Be\App\Moment\Potential\PaymentCapture;
use Be\App\Reason\PaymentGateway;
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
