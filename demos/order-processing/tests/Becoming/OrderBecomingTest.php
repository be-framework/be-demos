<?php

declare(strict_types=1);

namespace Be\App\Tests\Becoming;

use Be\App\Final\OrderConfirmed;
use Be\App\Input\OrderInput;
use Be\App\Module\AppModule;
use Be\Framework\Becoming;
use PHPUnit\Framework\TestCase;
use Ray\Di\Injector;

class OrderBecomingTest extends TestCase
{
    private Becoming $becoming;

    protected function setUp(): void
    {
        $injector = new Injector(new AppModule());
        $this->becoming = $injector->getInstance(Becoming::class);
    }

    public function testOrderInputBecomesOrderConfirmed(): void
    {
        $input = new OrderInput(
            cartId: 'CART-001',
            customerId: 'CUST-001',
            cardNumber: '4111111111111111',
            cardExpiry: '12/30',
            cardCvv: '123',
            postalCode: '1500001',
            streetAddress: '渋谷区神宮前1-1-1'
        );

        /** @var OrderConfirmed $final */
        $final = ($this->becoming)($input);

        $this->assertInstanceOf(OrderConfirmed::class, $final);
        $this->assertStringStartsWith('ORD-', $final->orderId);
        $this->assertSame('confirmed', $final->status);

        // Verify all Moments are present
        $this->assertNotNull($final->inventory);
        $this->assertNotNull($final->payment);
        $this->assertNotNull($final->shipping);

        // Verify Moment data via potentials
        $this->assertStringStartsWith('RSV-', $final->inventory->reservation->reservationId);
        $this->assertStringStartsWith('AUTH-', $final->payment->capture->authorizationCode);
        $this->assertNotEmpty($final->shipping->dispatch->trackingNumber);
    }
}
