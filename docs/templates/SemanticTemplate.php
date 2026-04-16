<?php

declare(strict_types=1);

namespace Be\Pattern\Template\Semantic; // TODO: replace `Template` with the real demo name

use Be\Framework\Attribute\Validate;

/**
 * Semantic template (Sinn).
 *
 * A Semantic class enforces a single domain rule. Rules:
 *   - one class per concept (naming matches the concept, e.g. `Quantity`)
 *   - one `#[Validate]` method that throws a domain exception on bad input
 *   - exceptions live under the demo's `src/Exception/` directory
 *   - link to schema.org in the docblock whenever a standard term exists
 *
 * Canonical example: demos/order-processing/src/Semantic/Quantity.php
 *
 * @link https://schema.org/ — TODO: replace with the matching concept
 */
final class SemanticTemplate
{
    #[Validate]
    public function validate(int $value): void
    {
        // TODO: replace with the real rule(s); throw a dedicated exception
        // from src/Exception/ with a machine-readable code string.
        if ($value < 0) {
            throw new \DomainException('value_out_of_range');
        }
    }
}
