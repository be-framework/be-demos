<?php

declare(strict_types=1);

namespace Be\Demo\Template\Reason; // TODO: replace `Template` with the real demo name (or use Be\App\Reason)

/**
 * Reason template (Sufficient Reason).
 *
 * A Reason is the business logic or external integration that allows a
 * Being or Moment to become what it is. Rules:
 *   - define an interface, not a concrete class
 *   - depend on the interface from every Being/Moment that needs it
 *   - bind the concrete implementation in the demo's Ray.Di module
 *   - keep one responsibility per interface (tests become trivial)
 *
 * Canonical example:
 *   interface:       demos/order-processing/src/Reason/InventoryReserverInterface.php
 *   implementation:  demos/order-processing/src/Reason/InventoryReserver.php
 */
interface ReasonInterfaceTemplate
{
    /**
     * TODO: describe the single domain fact this method produces.
     *
     * @return object TODO: replace with the real Potential or value type.
     */
    public function doSomething(string $input): object;
}
