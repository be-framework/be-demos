<?php

declare(strict_types=1);

namespace Be\Pattern\InsuranceClaim\Module;

use Be\Pattern\InsuranceClaim\Reason\AdjusterAllocator;
use Be\Pattern\InsuranceClaim\Reason\AdjusterAllocatorInterface;
use Be\Pattern\InsuranceClaim\Reason\ClaimRegistrar;
use Be\Pattern\InsuranceClaim\Reason\CoverageValidator;
use Be\Pattern\InsuranceClaim\Reason\DamageAppraiser;
use Be\Pattern\InsuranceClaim\Reason\DamageAppraiserInterface;
use Be\Pattern\InsuranceClaim\Reason\FraudDetector;
use Be\Pattern\InsuranceClaim\Reason\FraudDetectorInterface;
use Be\Pattern\InsuranceClaim\Reason\PaymentProcessor;
use Be\Pattern\InsuranceClaim\Reason\PaymentProcessorInterface;
use Be\Pattern\InsuranceClaim\Reason\PolicyRegistry;
use Be\Pattern\InsuranceClaim\Reason\PolicyRegistryInterface;
use Be\Pattern\InsuranceClaim\Reason\SettlementPolicy;
use Ray\Di\AbstractModule;

final class AppModule extends AbstractModule
{
    protected function configure(): void
    {
        // Claim processing - Reason bindings (via interfaces for testability)
        $this->bind(ClaimRegistrar::class);
        $this->bind(PolicyRegistryInterface::class)->to(PolicyRegistry::class);
        $this->bind(CoverageValidator::class);
        $this->bind(DamageAppraiserInterface::class)->to(DamageAppraiser::class);
        $this->bind(AdjusterAllocatorInterface::class)->to(AdjusterAllocator::class);
        $this->bind(FraudDetectorInterface::class)->to(FraudDetector::class);
        $this->bind(PaymentProcessorInterface::class)->to(PaymentProcessor::class);
        $this->bind(SettlementPolicy::class);
    }
}
