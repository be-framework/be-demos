<?php

declare(strict_types=1);

namespace Be\Demo\MedicalTriage\Semantic;

/**
 * Vitals Severity - internally derived classification ('critical'|'moderate'|'stable')
 *
 * Computed by {@see \Be\Demo\MedicalTriage\Reason\VitalsAssessor} from the
 * user-supplied vital signs, never user input. Declared as an empty semantic
 * stub so the Be Framework's semantic validator has a class to resolve when
 * the chain passes this variable between Beings.
 */
final class VitalsSeverity
{
}
