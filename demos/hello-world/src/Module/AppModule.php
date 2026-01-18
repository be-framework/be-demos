<?php

declare(strict_types=1);

namespace Be\Demo\Hello\Module;

use Be\Demo\Hello\Reason\Greeting;
use Ray\Di\AbstractModule;

final class AppModule extends AbstractModule
{
    protected function configure(): void
    {
        $this->bind(Greeting::class);
    }
}
