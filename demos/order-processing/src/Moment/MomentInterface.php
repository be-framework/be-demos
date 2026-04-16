<?php

declare(strict_types=1);

namespace Be\Pattern\OrderProcessing\Moment;

/**
 * Moment - Potential waiting to be realized
 *
 * A Moment is both:
 * 1. A part of a whole (Hegelian "moment")
 * 2. A potential state (Aristotelian "dynamis")
 *
 * Calling be() realizes the potential (energeia).
 */
interface MomentInterface
{
    /**
     * Realize this moment - become what you are meant to be
     */
    public function be(): void;
}
