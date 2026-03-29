<?php

declare(strict_types=1);

namespace Be\Demo\MedicalTriage\Reason;

use Be\Demo\MedicalTriage\Moment\Potential\TeamAlert;

/**
 * Team Dispatcher - Reason (stateless gateway)
 *
 * Creates TeamAlert moments that can be realized.
 */
final class TeamDispatcher implements TeamDispatcherInterface
{
    public function dispatch(string $triageCode): TeamAlert
    {
        // Determine team type based on triage code
        $alertId = sprintf(
            'ALERT-%s-%s-%s',
            $triageCode,
            date('YmdHis'),
            substr(md5($triageCode . time()), 0, 6)
        );

        // Return Moment with realize callback
        return new TeamAlert(
            $alertId,
            fn () => $this->notify($triageCode),
        );
    }

    private function notify(string $triageCode): string
    {
        // External system call to dispatch team
        // In production: return $this->api->dispatchTeam($triageCode);
        $teamType = match ($triageCode) {
            'RED' => 'TRAUMA',
            'YELLOW' => 'URGENT',
            default => 'GENERAL',
        };

        return sprintf('TEAM-%s-%03d', $teamType, random_int(1, 20));
    }
}
