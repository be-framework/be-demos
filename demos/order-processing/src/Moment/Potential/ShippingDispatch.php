<?php

declare(strict_types=1);

namespace Be\App\Moment\Potential;

use Be\App\Moment\MomentInterface;

/**
 * Shipping Dispatch - potential to dispatch shipment
 *
 * Born from ShippingArranger::prepare()
 * Realized by be() → dispatches the shipment
 */
final class ShippingDispatch implements MomentInterface
{
    /** @var callable(): void */
    private $realize;

    private bool $realized = false;

    /**
     * @param callable(): void $realize
     */
    public function __construct(
        public readonly string $trackingNumber,
        public readonly int $shippingRate,
        callable $realize,
    ) {
        $this->realize = $realize;
    }

    public function be(): void
    {
        if ($this->realized) {
            return; // Already realized - idempotent
        }

        $this->realized = true;
        ($this->realize)();
    }
}
