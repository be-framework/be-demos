<?php

declare(strict_types=1);

namespace Be\Demo\MedicalTriage\Module;

use Be\Demo\MedicalTriage\Reason\BedAllocator;
use Be\Demo\MedicalTriage\Reason\BedAllocatorInterface;
use Be\Demo\MedicalTriage\Reason\JTASProtocol;
use Be\Demo\MedicalTriage\Reason\ReferralPolicy;
use Be\Demo\MedicalTriage\Reason\TeamDispatcher;
use Be\Demo\MedicalTriage\Reason\TeamDispatcherInterface;
use Be\Demo\MedicalTriage\Reason\VitalsAssessor;
use Ray\Di\AbstractModule;

final class AppModule extends AbstractModule
{
    protected function configure(): void
    {
        // Reason bindings (concrete classes)
        $this->bind(VitalsAssessor::class);
        $this->bind(JTASProtocol::class);
        $this->bind(ReferralPolicy::class);

        // Reason bindings (via interfaces for testability)
        $this->bind(BedAllocatorInterface::class)->to(BedAllocator::class);
        $this->bind(TeamDispatcherInterface::class)->to(TeamDispatcher::class);
    }
}
