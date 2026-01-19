<?php

declare(strict_types=1);

namespace Be\App\Module;

use Be\App\Attribute\Address;
use Be\App\Attribute\Amount;
use Be\App\Attribute\CardNumber;
use Be\App\Attribute\CarrierId;
use Be\App\Attribute\ProductId;
use Be\App\Attribute\Quantity;
use Be\App\Attribute\WarehouseId;
use Be\App\Reason\AddressValidator;
use Be\App\Reason\CardValidator;
use Be\App\Reason\CarrierSelector;
use Be\App\Reason\Greeting;
use Be\App\Reason\InventoryChecker;
use Be\App\Reason\InventoryReserver;
use Be\App\Reason\InventoryReserverInterface;
use Be\App\Reason\PaymentGateway;
use Be\App\Reason\PaymentGatewayInterface;
use Be\App\Reason\ShippingArranger;
use Be\App\Reason\ShippingArrangerInterface;
use Be\App\Reason\WarehouseLocator;
use Ray\Di\AbstractModule;

final class AppModule extends AbstractModule
{
    protected function configure(): void
    {
        // Hello demo
        $this->bind(Greeting::class);

        // Order processing - Reason bindings (via interfaces for testability)
        $this->bind(WarehouseLocator::class);
        $this->bind(InventoryChecker::class);
        $this->bind(InventoryReserverInterface::class)->to(InventoryReserver::class);
        $this->bind(CardValidator::class);
        $this->bind(PaymentGatewayInterface::class)->to(PaymentGateway::class);
        $this->bind(AddressValidator::class);
        $this->bind(CarrierSelector::class);
        $this->bind(ShippingArrangerInterface::class)->to(ShippingArranger::class);

        // Inventory parameters (demo values)
        $this->bind()->annotatedWith(ProductId::class)->toInstance('PROD-001');
        $this->bind()->annotatedWith(Quantity::class)->toInstance(1);
        $this->bind()->annotatedWith(WarehouseId::class)->toInstance('WH-TOKYO-01');

        // Payment parameters (demo values)
        $this->bind()->annotatedWith(CardNumber::class)->toInstance('4111111111111111');
        $this->bind()->annotatedWith(Amount::class)->toInstance(10000);

        // Shipping parameters (demo values)
        $this->bind()->annotatedWith(CarrierId::class)->toInstance('YAMATO');
        $this->bind()->annotatedWith(Address::class)->toInstance('Tokyo, Japan');
    }
}
