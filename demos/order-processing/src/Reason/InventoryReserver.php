<?php

declare(strict_types=1);

namespace Be\Pattern\OrderProcessing\Reason;

use Be\Pattern\OrderProcessing\Moment\Potential\InventoryReservation;

/**
 * Inventory Reserver - Reason (stateless gateway)
 *
 * Creates InventoryReservation moments that can be realized.
 */
final class InventoryReserver implements InventoryReserverInterface
{
    public function lock(string $warehouseId, string $productId, int $quantity): InventoryReservation
    {
        // Create provisional reservation ID
        $reservationId = sprintf(
            'RSV-%s-%s-%d',
            substr($warehouseId, 3, 5),
            date('YmdHis'),
            random_int(1000, 9999)
        );

        // Return Moment with realize callback
        return new InventoryReservation(
            $reservationId,
            fn () => $this->confirm($reservationId),
        );
    }

    private function confirm(string $reservationId): void
    {
        // External system call to confirm reservation
        // In production: $this->api->confirmReservation($reservationId);
    }
}
