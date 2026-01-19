<?php

declare(strict_types=1);

namespace Be\App\Reason;

use Be\App\Moment\Potential\ShippingDispatch;

/**
 * Shipping Arranger Interface
 *
 * Enables testability through dependency injection.
 */
interface ShippingArrangerInterface
{
    public function prepare(string $carrierId, string $address): ShippingDispatch;
}
