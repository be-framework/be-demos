<?php

declare(strict_types=1);

namespace Be\Pattern\OrderProcessing;

require dirname(__DIR__) . '/vendor/autoload.php';

use Be\Framework\BecomingInterface;
use Be\Framework\Exception\SemanticVariableException;
use Be\Pattern\OrderProcessing\Final\OrderConfirmed;
use Be\Pattern\OrderProcessing\Input\OrderInput;
use Be\Pattern\OrderProcessing\Module\DevModule;
use Ray\Di\Injector;

use function assert;
use function dirname;
use function escapeshellarg;
use function passthru;

$injector = new Injector(new DevModule());
$becoming = $injector->getInstance(BecomingInterface::class);

$input = new OrderInput(
    cartId: 'CART-001',
    customerId: 'CUST-001',
    cardNumber: '4111111111111111',
    cardExpiry: '12/25',
    cardCvv: '123',
    postalCode: '150-0001',
    streetAddress: '渋谷区神宮前1-1-1',
);
try {
    $order = $becoming($input);
    assert($order instanceof OrderConfirmed);
    echo "Order confirmed: {$order->orderId}" . PHP_EOL;
} catch (SemanticVariableException $e) {
    $messages = $e->getErrors()->getMessages('ja');
    $errorMessage = $messages[0] ?? $e->getMessage();
    echo $errorMessage . PHP_EOL;
}

echo "\n--- stree ---\n";
passthru('vendor/bin/stree ' . escapeshellarg(dirname(__DIR__) . '/var/log/order-processing.json'));
