<?php

declare(strict_types=1);

namespace Be\App\Reason;

use Be\App\Moment\Potential\ShippingDispatch;

/**
 * Shipping Arranger - Reason (stateless gateway)
 *
 * Creates ShippingDispatch moments that can be realized.
 */
final class ShippingArranger
{
    public function prepare(string $carrierId, string $address): ShippingDispatch
    {
        $rate = match ($carrierId) {
            'YAMATO' => 800,
            'SAGAWA' => 750,
            'JPPOST' => 500,
            default => 1000,
        };

        $trackingNumber = sprintf('%s-%s', $carrierId, date('YmdHis') . random_int(1000, 9999));

        // Return Moment with realize callback
        return new ShippingDispatch(
            $trackingNumber,
            $rate,
            fn () => $this->dispatch($trackingNumber),
        );
    }

    private function dispatch(string $trackingNumber): void
    {
        // External system call to dispatch shipment
        // In production: $this->api->dispatch($trackingNumber);
    }
}
