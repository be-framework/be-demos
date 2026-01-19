<?php

declare(strict_types=1);

namespace Be\App\Tests\Reason;

use Be\App\Moment\Potential\PaymentCapture;
use Be\App\Reason\PaymentGateway;
use PHPUnit\Framework\TestCase;

class PaymentGatewayTest extends TestCase
{
    private PaymentGateway $gateway;

    protected function setUp(): void
    {
        $this->gateway = new PaymentGateway();
    }

    public function testAuthorizeReturnsMoment(): void
    {
        $capture = $this->gateway->authorize('4111111111111111', 10000);

        $this->assertInstanceOf(PaymentCapture::class, $capture);
        $this->assertStringStartsWith('AUTH-10000-', $capture->authorizationCode);
        $this->assertSame(10000, $capture->amount);
    }

    public function testMomentCanBeRealized(): void
    {
        $capture = $this->gateway->authorize('4111111111111111', 10000);

        // Before realization, no transaction ID
        $this->assertNull($capture->getTransactionId());

        // Realize the potential
        $capture->be();

        // After realization, transaction ID is generated
        $this->assertStringStartsWith('TXN-', $capture->getTransactionId());
    }
}
