<?php

declare(strict_types=1);

namespace Be\Demo\MedicalTriage\Moment;

use Be\Demo\MedicalTriage\Moment\Potential\TeamAlert;
use Be\Demo\MedicalTriage\Reason\TeamDispatcherInterface;
use Ray\Di\Di\Inject;
use Ray\InputQuery\Attribute\Input;

/**
 * Team Alerted - Moment (part + potential)
 *
 * Part of EmergencyAdmitted, holding the potential to dispatch emergency team.
 */
final readonly class TeamAlerted implements MomentInterface
{
    public TeamAlert $alert;

    public function __construct(
        #[Input] public string $triageCode,
        #[Inject] TeamDispatcherInterface $dispatcher,
    ) {
        // Born: create potential (prepare team alert)
        $this->alert = $dispatcher->dispatch($triageCode);
    }

    public function be(): void
    {
        $this->alert->be();
    }
}
