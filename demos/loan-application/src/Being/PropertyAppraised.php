<?php

declare(strict_types=1);

namespace Be\Pattern\LoanApplication\Being;

use Be\Pattern\LoanApplication\Reason\PropertyAppraisalInterface;
use Ray\Di\Di\Inject;
use Ray\InputQuery\Attribute\Input;

/**
 * Property Appraised - Being (Stage 2 parallel branch)
 *
 * Appraises the property value for collateral assessment.
 */
final readonly class PropertyAppraised
{
    public int $appraisedValue;

    public function __construct(
        #[Input] public string $propertyAddress,
        #[Input] public string $propertyType,
        #[Inject] PropertyAppraisalInterface $appraiser
    ) {
        $this->appraisedValue = $appraiser->appraise($propertyAddress, $propertyType);
    }
}
