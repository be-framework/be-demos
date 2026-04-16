<?php

declare(strict_types=1);

namespace Be\Pattern\OrderProcessing\Being\Shipping;

use Ray\InputQuery\Attribute\Input;
use Ray\Di\Di\Inject;
use Be\Pattern\OrderProcessing\Reason\CarrierSelector;

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
