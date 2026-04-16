<?php

declare(strict_types=1);

namespace Be\Pattern\OrderProcessing\Exception;

use Be\Framework\Attribute\Message;
use DomainException;

#[Message([
    'en' => 'Payment processing failed. Please check your card details.',
    'ja' => '決済処理に失敗しました。カード情報を確認してください。'
])]
final class PaymentFailedException extends DomainException
{
}
