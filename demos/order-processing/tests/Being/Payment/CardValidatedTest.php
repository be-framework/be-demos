<?php

declare(strict_types=1);

namespace Be\App\Tests\Being\Payment;

use Be\App\Being\Payment\CardValidated;
use Be\App\Reason\CardValidator;
use PHPUnit\Framework\TestCase;

class CardValidatedTest extends TestCase
{
    public function testCardValid(): void
    {
        $validator = new CardValidator();
        $futureYear = (int) date('y') + 1;
        $being = new CardValidated('4111111111111111', "12/{$futureYear}", '123', $validator);

        $this->assertSame('4111111111111111', $being->cardNumber);
        $this->assertSame("12/{$futureYear}", $being->cardExpiry);
        $this->assertSame('123', $being->cardCvv);
        $this->assertTrue($being->valid);
    }

    public function testCardExpired(): void
    {
        $validator = new CardValidator();
        $being = new CardValidated('4111111111111111', '01/20', '123', $validator);

        $this->assertFalse($being->valid);
    }
}
