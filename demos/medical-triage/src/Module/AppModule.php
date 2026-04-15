<?php

declare(strict_types=1);

namespace Be\Pattern\MedicalTriage\Module;

use Be\Pattern\MedicalTriage\Reason\JTASProtocol;
use Ray\Di\AbstractModule;

final class AppModule extends AbstractModule
{
    protected function configure(): void
    {
        $this->bind(JTASProtocol::class);
    }
}
