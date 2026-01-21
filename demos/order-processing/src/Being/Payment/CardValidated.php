<?php

declare(strict_types=1);

namespace Be\App\Being\Payment;

use Ray\InputQuery\Attribute\Input;
use Ray\Di\Di\Inject;
use Be\App\Reason\CardValidator;

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
