<?php

declare(strict_types=1);

namespace Be\Demo\InsuranceClaim\Module;

use Be\Demo\InsuranceClaim\Reason\AdjusterAllocator;
use Be\Demo\InsuranceClaim\Reason\AdjusterAllocatorInterface;
use Be\Demo\InsuranceClaim\Reason\ClaimRegistrar;
use Be\Demo\InsuranceClaim\Reason\CoverageValidator;
use Be\Demo\InsuranceClaim\Reason\DamageAppraiser;
use Be\Demo\InsuranceClaim\Reason\DamageAppraiserInterface;
use Be\Demo\InsuranceClaim\Reason\FraudDetector;
use Be\Demo\InsuranceClaim\Reason\FraudDetectorInterface;
use Be\Demo\InsuranceClaim\Reason\PaymentProcessor;
use Be\Demo\InsuranceClaim\Reason\PaymentProcessorInterface;
use Be\Demo\InsuranceClaim\Reason\PolicyRegistry;
use Be\Demo\InsuranceClaim\Reason\PolicyRegistryInterface;
use Be\Demo\InsuranceClaim\Reason\SettlementPolicy;
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
