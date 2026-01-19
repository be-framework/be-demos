<?php

declare(strict_types=1);

namespace Be\App\Tests\Moment;

use Be\App\Moment\PaymentCompleted;
use Be\App\Reason\PaymentGateway;
use PHPUnit\Framework\TestCase;

class PaymentCompletedTest extends TestCase
{
    public function testPaymentCompleted(): void
    {
        $gateway = new PaymentGateway();
        $moment = new PaymentCompleted('4111111111111111', 10000, $gateway);

        $this->assertSame('4111111111111111', $moment->cardNumber);
        $this->assertSame(10000, $moment->amount);
        $this->assertStringStartsWith('AUTH-', $moment->capture->authorizationCode);
    }

    public function testMomentCanBeRealized(): void
    {
        $gateway = new PaymentGateway();
        $moment = new PaymentCompleted('4111111111111111', 10000, $gateway);

        // Realize the Moment
        $moment->be();

        // Transaction ID is generated after be()
        $this->assertStringStartsWith('TXN-', $moment->capture->getTransactionId());
    }
}
