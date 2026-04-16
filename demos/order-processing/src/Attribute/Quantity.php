<?php

declare(strict_types=1);

namespace Be\Pattern\OrderProcessing\Attribute;

use Attribute;
use Ray\Di\Di\Qualifier;

#[Attribute(Attribute::TARGET_PARAMETER)]
#[Qualifier]
final class Quantity
{
}
