<?php

declare(strict_types=1);

namespace Be\Pattern\OrderProcessing\Semantic;

use Be\Pattern\OrderProcessing\Exception\InvalidWarehouseIdException;
use Be\Framework\Attribute\Validate;

/**
 * Warehouse ID
 *
 * @link https://schema.org/identifier
 */
final class WarehouseId
{
    #[Validate]
    public function validate(string $warehouseId): void
    {
        if (empty(trim($warehouseId))) {
            throw new InvalidWarehouseIdException();
        }

        // Format: WH-XXX (alphanumeric/hyphen after prefix)
        if (!preg_match('/^WH-[A-Za-z0-9-]+$/', $warehouseId)) {
            throw new InvalidWarehouseIdException();
        }
    }
}
