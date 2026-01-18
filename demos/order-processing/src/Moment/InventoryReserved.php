<?php

declare(strict_types=1);

namespace Be\App\Moment;

use Be\App\Attribute\ProductId;
use Be\App\Attribute\Quantity;
use Be\App\Attribute\WarehouseId;
use Be\App\Moment\Potential\InventoryReservation;
use Be\App\Reason\InventoryReserver;
use Ray\Di\Di\Inject;

/**
 * Inventory Reserved - Moment (part + potential)
 *
 * Part of OrderConfirmed, holding the potential to confirm reservation.
 */
final readonly class InventoryReserved implements MomentInterface
{
    public InventoryReservation $reservation;

    public function __construct(
        #[ProductId] public string $productId,
        #[Quantity] public int $quantity,
        #[WarehouseId] public string $warehouseId,
        #[Inject] InventoryReserver $reserver,
    ) {
        // Born: create potential (lock inventory temporarily)
        $this->reservation = $reserver->lock($warehouseId, $productId, $quantity);
    }

    public function be(): void
    {
        $this->reservation->be();
    }
}
