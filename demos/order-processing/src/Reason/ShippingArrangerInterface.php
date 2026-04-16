<?php

declare(strict_types=1);

namespace Be\Pattern\OrderProcessing\Reason;

use Be\Pattern\OrderProcessing\Moment\Potential\ShippingDispatch;

/**
 * Shipping Arranger Interface
 *
 * Enables testability through dependency injection.
 */
interface ShippingArrangerInterface
{
    public function prepare(string $carrierId, string $address): ShippingDispatch;
}
