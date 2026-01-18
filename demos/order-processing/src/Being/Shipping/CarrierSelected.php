<?php

declare(strict_types=1);

namespace Be\App\Being\Shipping;

use Ray\InputQuery\Attribute\Input;
use Ray\Di\Di\Inject;
use Be\App\Reason\CarrierSelector;

final readonly class CarrierSelected
{
    public string $carrierId;
    public string $carrierName;

    public function __construct(
        #[Input] public string $postalCode,
        #[Inject] CarrierSelector $selector
    ) {
        $carrier = $selector->select($postalCode);
        $this->carrierId = $carrier['id'];
        $this->carrierName = $carrier['name'];
    }
}
