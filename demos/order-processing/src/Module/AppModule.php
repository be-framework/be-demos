<?php

declare(strict_types=1);

namespace Be\Pattern\OrderProcessing\Module;

use Be\Framework\Module\BeModule;
use Be\Pattern\OrderProcessing\Attribute\Address;
use Be\Pattern\OrderProcessing\Attribute\Amount;
use Be\Pattern\OrderProcessing\Attribute\CardNumber;
use Be\Pattern\OrderProcessing\Attribute\CarrierId;
use Be\Pattern\OrderProcessing\Attribute\ProductId;
use Be\Pattern\OrderProcessing\Attribute\Quantity;
use Be\Pattern\OrderProcessing\Attribute\WarehouseId;
use Be\Pattern\OrderProcessing\Reason\AddressValidator;
use Be\Pattern\OrderProcessing\Reason\CardValidator;
use Be\Pattern\OrderProcessing\Reason\CarrierSelector;
use Be\Pattern\OrderProcessing\Reason\InventoryChecker;
use Be\Pattern\OrderProcessing\Reason\InventoryReserver;
use Be\Pattern\OrderProcessing\Reason\InventoryReserverInterface;
use Be\Pattern\OrderProcessing\Reason\PaymentGateway;
use Be\Pattern\OrderProcessing\Reason\PaymentGatewayInterface;
use Be\Pattern\OrderProcessing\Reason\ShippingArranger;
use Be\Pattern\OrderProcessing\Reason\ShippingArrangerInterface;
use Be\Pattern\OrderProcessing\Reason\WarehouseLocator;
use Ray\Di\AbstractModule;

final class AppModule extends AbstractModule
{
    protected function configure(): void
    {
        $this->install(new BeModule('Be\Pattern\OrderProcessing\Semantic'));

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
