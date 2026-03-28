<?php

declare(strict_types=1);

namespace Be\Demo\Hello\Module;

use Be\Demo\Hello\Reason\Greeting;
use Be\Framework\Module\BeModule;
use Ray\Di\AbstractModule;

final class AppModule extends AbstractModule
{
    protected function configure(): void
    {
        // Install BeModule with demo's semantic namespace
        $this->install(new BeModule('Be\Demo\Hello\Semantic'));

        $this->bind(Greeting::class);
    }
}
