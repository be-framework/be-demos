<?php

declare(strict_types=1);

namespace Be\Pattern\MedicalTriage\Module;

use Be\Framework\Module\BeModule;
use Be\Pattern\MedicalTriage\Reason\JTASProtocol;
use Ray\Di\AbstractModule;

final class AppModule extends AbstractModule
{
    protected function configure(): void
    {
        $this->install(new BeModule('Be\Pattern\MedicalTriage\Semantic'));

        $this->bind(JTASProtocol::class);
    }
}
