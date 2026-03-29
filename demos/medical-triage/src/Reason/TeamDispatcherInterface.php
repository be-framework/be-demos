<?php

declare(strict_types=1);

namespace Be\Demo\MedicalTriage\Reason;

use Be\Demo\MedicalTriage\Moment\Potential\TeamAlert;

/**
 * Team Dispatcher Interface
 *
 * Enables testability through dependency injection.
 */
interface TeamDispatcherInterface
{
    public function dispatch(string $triageCode): TeamAlert;
}
