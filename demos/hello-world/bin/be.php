<?php

declare(strict_types=1);

namespace Be\Pattern\Hello;

require dirname(__DIR__) . '/vendor/autoload.php';

use Be\Framework\BecomingInterface;
use Be\Pattern\Hello\Final\Hello;
use Be\Pattern\Hello\Input\HelloInput;
use Be\Pattern\Hello\Module\DevModule;
use Ray\Di\Injector;

use function assert;
use function dirname;
use function escapeshellarg;
use function passthru;
use function printf;

$injector = new Injector(new DevModule());
$becoming = $injector->getInstance(BecomingInterface::class);

$final = $becoming(new HelloInput(name: 'World'));
assert($final instanceof Hello);
printf("greeting: %s\n", $final->greeting);

echo "\n--- stree ---\n";
passthru('vendor/bin/stree ' . escapeshellarg(dirname(__DIR__) . '/var/log/hello-world.json'));
