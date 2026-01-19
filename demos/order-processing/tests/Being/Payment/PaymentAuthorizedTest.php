<?php

declare(strict_types=1);

namespace Be\App\Tests\Being\Payment;

use Be\App\Being\Payment\PaymentAuthorized;
use Be\App\Moment\Potential\PaymentCapture;
use Be\App\Reason\PaymentGateway;
use PHPUnit\Framework\TestCase;

class PaymentAuthorizedTest extends TestCase
{
    public function testPaymentAuthorized(): void
    {
        $gateway = new PaymentGateway();
        $being = new PaymentAuthorized('4111111111111111', 10000, $gateway);

        $this->assertSame('4111111111111111', $being->cardNumber);
        $this->assertSame(10000, $being->amount);
        $this->assertInstanceOf(PaymentCapture::class, $being->capture);
        $this->assertStringStartsWith('AUTH-10000-', $being->capture->authorizationCode);
    }

    public function testPotentialCanBeRealized(): void
    {
        $gateway = new PaymentGateway();
        $being = new PaymentAuthorized('4111111111111111', 10000, $gateway);

        // Realize the potential
        $being->capture->be();

        // Transaction ID is generated after be()
        $this->assertStringStartsWith('TXN-', $being->capture->getTransactionId());
    }
}
