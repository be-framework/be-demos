<?php

declare(strict_types=1);

namespace Be\Pattern\OrderProcessing\Being\Payment;

use Ray\InputQuery\Attribute\Input;
use Ray\Di\Di\Inject;
use Be\Pattern\OrderProcessing\Reason\CardValidator;

final readonly class CardValidated
{
    public bool $valid;

    public function __construct(
        #[Input] public string $cardNumber,
        #[Input] public string $cardExpiry,
        #[Input] public string $cardCvv,
        #[Inject] CardValidator $validator
    ) {
        // CVV format is validated by Semantic layer (Semantic/CardCvv)
        $this->valid = $validator->validate($cardNumber, $cardExpiry);
    }
}
