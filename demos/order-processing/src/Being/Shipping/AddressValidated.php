<?php

declare(strict_types=1);

namespace Be\App\Being\Shipping;

use Ray\InputQuery\Attribute\Input;
use Ray\Di\Di\Inject;
use Be\App\Reason\AddressValidator;

final readonly class AddressValidated
{
    public bool $valid;
    public string $normalizedAddress;

    public function __construct(
        #[Input] public string $postalCode,
        #[Input] public string $streetAddress,
        #[Inject] AddressValidator $validator
    ) {
        $this->valid = $validator->validate($postalCode, $streetAddress);
        $this->normalizedAddress = $validator->normalize($postalCode, $streetAddress);
    }
}
