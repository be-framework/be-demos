<?php

declare(strict_types=1);

namespace Be\Pattern\Hello\Module;

use Be\Framework\Module\BeModule;
use Be\Pattern\Hello\Reason\Greeting;
use Ray\Di\AbstractModule;

final class AppModule extends AbstractModule
{
    protected function configure(): void
    {
        $this->install(new BeModule('Be\Pattern\Hello\Semantic'));

        $this->bind(Greeting::class);
    }
}
