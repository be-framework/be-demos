<?php

declare(strict_types=1);

namespace Be\Demo\MedicalTriage\Moment\Potential;

use Be\Demo\MedicalTriage\Moment\MomentInterface;

/**
 * Team Alert - potential to dispatch emergency team
 *
 * Born from TeamDispatcher::dispatch()
 * Realized by be() -> dispatches the team
 */
final class TeamAlert implements MomentInterface
{
    /** @var callable(): string */
    private $realize;

    private ?string $teamId = null;

    /**
     * @param callable(): string $realize
     */
    public function __construct(
        public readonly string $alertId,
        callable $realize,
    ) {
        $this->realize = $realize;
    }

    public function be(): void
    {
        if ($this->teamId !== null) {
            return; // Already realized - idempotent
        }

        $this->teamId = ($this->realize)();
    }

    public function getTeamId(): ?string
    {
        return $this->teamId;
    }
}
