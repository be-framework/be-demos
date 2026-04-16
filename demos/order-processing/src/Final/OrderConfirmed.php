<?php

declare(strict_types=1);

namespace Be\Pattern\OrderProcessing\Final;

use Be\Pattern\OrderProcessing\Context\OrderFinalizedContext;
use Be\Pattern\OrderProcessing\Moment\InventoryReserved;
use Be\Pattern\OrderProcessing\Moment\PaymentCompleted;
use Be\Pattern\OrderProcessing\Moment\ShippingArranged;
use Be\Framework\SemanticLog\Been;
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
    public Been $been;

    public function __construct(
        #[Inject] public InventoryReserved $inventory,
        #[Inject] public PaymentCompleted $payment,
        #[Inject] public ShippingArranged $shipping,
        #[Inject] Been $been,
    ) {
        // Self-completion: realize all Moments (parts of self)
        $this->inventory->be();
        $this->payment->be();
        $this->shipping->be();

        $this->orderId = $this->generateOrderId();
        $this->status = 'confirmed';
        $this->been = $been->with(new OrderFinalizedContext(
            orderId: $this->orderId,
            status: $this->status,
        ));

        $event = $this->been->events[0];
        assert($event instanceof OrderFinalizedContext);
        assert($event->status === 'confirmed');
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
