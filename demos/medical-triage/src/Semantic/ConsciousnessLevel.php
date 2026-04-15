<?php

declare(strict_types=1);

namespace Be\Pattern\MedicalTriage\Semantic;

use Be\Pattern\MedicalTriage\Exception\InvalidConsciousnessException;
use Be\Framework\Attribute\Validate;

/**
 * Consciousness Level (Japan Coma Scale / JCS)
 *
 * Valid JCS values:
 *   0   - Alert
 *   1   - Almost clear, slightly dull
 *   2   - Disoriented (time/place/person)
 *   3   - Cannot recall name/birthday
 *   10  - Opens eyes on call
 *   20  - Opens eyes on pain (loud voice)
 *   30  - Opens eyes on repeated stimuli
 *   100 - Motor response to pain (purposeful)
 *   200 - Motor response to pain (withdrawal)
 *   300 - No motor response
 *
 * @link https://en.wikipedia.org/wiki/Japan_Coma_Scale
 */
final class ConsciousnessLevel
{
    private const VALID_JCS_VALUES = [0, 1, 2, 3, 10, 20, 30, 100, 200, 300];

    #[Validate]
    public function validate(int $consciousnessLevel): void
    {
        if (!in_array($consciousnessLevel, self::VALID_JCS_VALUES, true)) {
            throw new InvalidConsciousnessException();
        }
    }
}
