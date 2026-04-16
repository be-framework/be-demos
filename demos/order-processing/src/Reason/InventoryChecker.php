<?php

declare(strict_types=1);

namespace Be\Pattern\OrderProcessing\Reason;

/**
 * Inventory Checker - Reason for quantity verification
 */
final class InventoryChecker
{
    public function check(string $warehouseId, string $productId, int $quantity): bool
    {
        // Demo: always available if quantity <= 10
        return $quantity <= 10;
    }
}
