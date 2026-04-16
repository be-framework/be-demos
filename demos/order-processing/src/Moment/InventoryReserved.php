<?php

declare(strict_types=1);

namespace Be\Pattern\OrderProcessing\Moment;

use Be\Pattern\OrderProcessing\Attribute\ProductId;
use Be\Pattern\OrderProcessing\Attribute\Quantity;
use Be\Pattern\OrderProcessing\Attribute\WarehouseId;
use Be\Pattern\OrderProcessing\Moment\Potential\InventoryReservation;
use Be\Pattern\OrderProcessing\Reason\InventoryReserverInterface;
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
        #[Inject] InventoryReserverInterface $reserver,
    ) {
        // Born: create potential (lock inventory temporarily)
        $this->reservation = $reserver->lock($warehouseId, $productId, $quantity);
    }

    public function be(): void
    {
        $this->reservation->be();
    }
}
