<?php

declare(strict_types=1);

namespace Be\Demo\MedicalTriage\Module;

use Be\Demo\MedicalTriage\Reason\JTASProtocol;
use Ray\Di\AbstractModule;

final class AppModule extends AbstractModule
{
    protected function configure(): void
    {
        $this->bind(JTASProtocol::class);
    }
}
