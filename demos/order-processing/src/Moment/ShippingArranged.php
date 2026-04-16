<?php

declare(strict_types=1);

namespace Be\Pattern\OrderProcessing\Moment;

use Be\Pattern\OrderProcessing\Attribute\Address;
use Be\Pattern\OrderProcessing\Attribute\CarrierId;
use Be\Pattern\OrderProcessing\Moment\Potential\ShippingDispatch;
use Be\Pattern\OrderProcessing\Reason\ShippingArrangerInterface;
use Ray\Di\Di\Inject;

/**
 * Shipping Arranged - Moment (part + potential)
 *
 * Part of OrderConfirmed, holding the potential to dispatch shipment.
 */
final readonly class ShippingArranged implements MomentInterface
{
    public ShippingDispatch $dispatch;

    public function __construct(
        #[CarrierId] public string $carrierId,
        #[Address] public string $address,
        #[Inject] ShippingArrangerInterface $arranger,
    ) {
        // Born: create potential (prepare shipping label)
        $this->dispatch = $arranger->prepare($carrierId, $address);
    }

    public function be(): void
    {
        $this->dispatch->be();
    }
}
