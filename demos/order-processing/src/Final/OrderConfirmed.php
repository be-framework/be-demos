<?php

declare(strict_types=1);

namespace Be\App\Final;

use Be\App\Moment\InventoryReserved;
use Be\App\Moment\PaymentCompleted;
use Be\App\Moment\ShippingArranged;
use Ray\Di\Di\Inject;

/**
 * Order Confirmed - Final (convergence point)
 *
 * Diamond metamorphosis: three Moments converge into one Final.
 * All Moments are realized here through self-completion, not command.
 *
 * @link https://schema.org/Order
 */
final readonly class OrderConfirmed
{
    public string $orderId;
    public string $status;

    public function __construct(
        #[Inject] public InventoryReserved $inventory,
        #[Inject] public PaymentCompleted $payment,
        #[Inject] public ShippingArranged $shipping,
    ) {
        // Self-completion: realize all Moments (parts of self)
        $this->inventory->be();
        $this->payment->be();
        $this->shipping->be();

        $this->orderId = $this->generateOrderId();
        $this->status = 'confirmed';
    }

    private function generateOrderId(): string
    {
        return sprintf(
            'ORD-%s-%s',
            date('Ymd'),
            substr(md5(
                $this->inventory->reservation->reservationId .
                $this->payment->capture->authorizationCode
            ), 0, 8)
        );
    }
}
