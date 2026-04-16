<?php

declare(strict_types=1);

namespace Be\Pattern\OrderProcessing\Reason;

use Be\Pattern\OrderProcessing\Moment\Potential\InventoryReservation;

/**
 * Inventory Reserver Interface
 *
 * Enables testability through dependency injection.
 */
interface InventoryReserverInterface
{
    public function lock(string $warehouseId, string $productId, int $quantity): InventoryReservation;
}
