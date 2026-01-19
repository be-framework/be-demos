<?php

declare(strict_types=1);

namespace Be\App\Reason;

use Be\App\Moment\Potential\InventoryReservation;

/**
 * Inventory Reserver Interface
 *
 * Enables testability through dependency injection.
 */
interface InventoryReserverInterface
{
    public function lock(string $warehouseId, string $productId, int $quantity): InventoryReservation;
}
