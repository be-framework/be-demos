<?php

declare(strict_types=1);

namespace Be\App\Reason;

/**
 * Warehouse Locator - Reason for stock location
 */
final class WarehouseLocator
{
    public function locate(string $productId): string
    {
        // Demo: return warehouse based on product prefix
        return match (true) {
            str_starts_with($productId, 'A') => 'WH-TOKYO-01',
            str_starts_with($productId, 'B') => 'WH-OSAKA-01',
            default => 'WH-CENTRAL-01'
        };
    }
}
