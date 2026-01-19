<?php

declare(strict_types=1);

namespace Be\App\Tests\Final;

use Be\App\Final\OrderConfirmed;
use Be\App\Moment\InventoryReserved;
use Be\App\Moment\PaymentCompleted;
use Be\App\Moment\ShippingArranged;
use Be\App\Reason\InventoryReserver;
use Be\App\Reason\PaymentGateway;
use Be\App\Reason\ShippingArranger;
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
        $final = new OrderConfirmed($inventory, $payment, $shipping);

        $this->assertStringStartsWith('ORD-', $final->orderId);
        $this->assertSame('confirmed', $final->status);
        $this->assertSame($inventory, $final->inventory);
        $this->assertSame($payment, $final->payment);
        $this->assertSame($shipping, $final->shipping);
    }

    public function testOrderIdFormat(): void
    {
        $inventoryReserver = new InventoryReserver();
        $inventory = new InventoryReserved('PROD-001', 5, 'WH-TOKYO-01', $inventoryReserver);

        $paymentGateway = new PaymentGateway();
        $payment = new PaymentCompleted('4111111111111111', 10000, $paymentGateway);

        $shippingArranger = new ShippingArranger();
        $shipping = new ShippingArranged('YAMATO', '〒150-0001 渋谷区神宮前1-1-1', $shippingArranger);

        $final = new OrderConfirmed($inventory, $payment, $shipping);

        // Order ID format: ORD-YYYYMMDD-xxxxxxxx
        $this->assertMatchesRegularExpression('/^ORD-\d{8}-[a-f0-9]{8}$/', $final->orderId);
    }
}
