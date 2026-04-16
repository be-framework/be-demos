<?php

declare(strict_types=1);

namespace Be\Pattern\OrderProcessing\Tests\Final;

use Be\Framework\SemanticLog\Been;
use Be\Pattern\OrderProcessing\Context\OrderFinalizedContext;
use Be\Pattern\OrderProcessing\Final\OrderConfirmed;
use Be\Pattern\OrderProcessing\Moment\InventoryReserved;
use Be\Pattern\OrderProcessing\Moment\PaymentCompleted;
use Be\Pattern\OrderProcessing\Moment\ShippingArranged;
use Be\Pattern\OrderProcessing\Reason\InventoryReserver;
use Be\Pattern\OrderProcessing\Reason\PaymentGateway;
use Be\Pattern\OrderProcessing\Reason\ShippingArranger;
use Koriym\SemanticLogger\SemanticLogger;
use PHPUnit\Framework\TestCase;

class OrderConfirmedTest extends TestCase
{
    public function testOrderConfirmed(): void
    {
        // Create all three Moments (part + potential)
        $inventoryReserver = new InventoryReserver();
        $inventory = new InventoryReserved('PROD-001', 5, 'WH-TOKYO-01', $inventoryReserver);

        $paymentGateway = new PaymentGateway();
        $payment = new PaymentCompleted('4111111111111111', 10000, $paymentGateway);

        $shippingArranger = new ShippingArranger();
        $shipping = new ShippingArranged('YAMATO', '〒150-0001 渋谷区神宮前1-1-1', $shippingArranger);

        // Converge into Final (realizes all potentials)
        $been = new Been(new SemanticLogger());
        $final = new OrderConfirmed($inventory, $payment, $shipping, $been);

        $this->assertStringStartsWith('ORD-', $final->orderId);
        $this->assertSame('confirmed', $final->status);
        $this->assertSame($inventory, $final->inventory);
        $this->assertSame($payment, $final->payment);
        $this->assertSame($shipping, $final->shipping);
        $this->assertCount(1, $final->been->events);
        $this->assertInstanceOf(OrderFinalizedContext::class, $final->been->events[0]);
    }

    public function testOrderIdFormat(): void
    {
        $inventoryReserver = new InventoryReserver();
        $inventory = new InventoryReserved('PROD-001', 5, 'WH-TOKYO-01', $inventoryReserver);

        $paymentGateway = new PaymentGateway();
        $payment = new PaymentCompleted('4111111111111111', 10000, $paymentGateway);

        $shippingArranger = new ShippingArranger();
        $shipping = new ShippingArranged('YAMATO', '〒150-0001 渋谷区神宮前1-1-1', $shippingArranger);

        $been = new Been(new SemanticLogger());
        $final = new OrderConfirmed($inventory, $payment, $shipping, $been);

        // Order ID format: ORD-YYYYMMDD-xxxxxxxx
        $this->assertMatchesRegularExpression('/^ORD-\d{8}-[a-f0-9]{8}$/', $final->orderId);
    }
}
