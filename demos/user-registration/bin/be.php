<?php

declare(strict_types=1);

namespace Be\Pattern\UserRegistration;

require dirname(__DIR__) . '/vendor/autoload.php';

use Be\Framework\BecomingInterface;
use Be\Pattern\UserRegistration\Final\UserRegistered;
use Be\Pattern\UserRegistration\Input\RegistrationInput;
use Be\Pattern\UserRegistration\Module\DevModule;
use Ray\Di\Injector;

use function assert;
use function dirname;
use function escapeshellarg;
use function passthru;
use function printf;

$injector = new Injector(new DevModule());
$becoming = $injector->getInstance(BecomingInterface::class);

$final = $becoming(new RegistrationInput(
    email: 'alice@example.com',
    password: 'Str0ngP@ss',
    displayName: 'Alice',
));
assert($final instanceof UserRegistered);
printf("userId: %s\n", $final->userId);

echo "\n--- stree ---\n";
passthru('vendor/bin/stree ' . escapeshellarg(dirname(__DIR__) . '/var/log/user-registration.json'));
