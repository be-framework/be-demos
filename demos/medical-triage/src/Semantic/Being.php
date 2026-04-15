<?php

declare(strict_types=1);

namespace Be\Demo\MedicalTriage\Semantic;

/**
 * Being - branching discriminator carried between a Being state and its Finals
 *
 * Holds an {@see \Be\Demo\MedicalTriage\Being\Path\ImmediatePath},
 * {@see \Be\Demo\MedicalTriage\Being\Path\UrgentPath}, or
 * {@see \Be\Demo\MedicalTriage\Being\Path\NonUrgentPath} marker; the Be
 * Framework's type matcher selects the Final by the runtime class of the
 * value. Declared as an empty semantic stub - the discriminator itself is
 * already type-safe via PHP's union type.
 */
final class Being
{
}
