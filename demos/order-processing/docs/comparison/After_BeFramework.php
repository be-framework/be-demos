<?php

/**
 * After: Order Processing with Be Framework
 *
 * The same functionality expressed through ontological metamorphosis.
 * Notice how each concept has its place, and the diamond convergence is declarative.
 */

declare(strict_types=1);

namespace Be\App;

// ═══════════════════════════════════════════════════════════════════════════
// INPUT - The starting point (Aristotle's Dynamis - Potentiality)
// ═══════════════════════════════════════════════════════════════════════════

use Be\Framework\Attribute\Be;

#[Be([Final\OrderConfirmed::class])]
final readonly class Input\OrderInput
{
    public function __construct(
        public string $cartId,
        public string $customerId,
        public string $productId,
        public int $quantity,
        public string $cardNumber,
        public string $cardExpiry,
        public string $cardCvv,
        public string $postalCode,
        public string $streetAddress,
        public int $amount,
    ) {}
}

// ═══════════════════════════════════════════════════════════════════════════
// SEMANTIC - Validation as meaning (Frege's Sinn + 言霊)
// ═══════════════════════════════════════════════════════════════════════════

// Each semantic class encapsulates its own validation - reusable across contexts

final class Semantic\CardNumber
{
    #[Validate]
    public function validate(string $cardNumber): void
    {
        if (!$this->luhn($cardNumber)) {
            throw new Exception\InvalidCardNumberException();
        }
    }
    // ... Luhn algorithm
}

final class Semantic\PostalCode
{
    #[Validate]
    public function validate(string $postalCode): void
    {
        if (!preg_match('/^\d{3}-?\d{4}$/', $postalCode)) {
            throw new Exception\InvalidPostalCodeException();
        }
    }
}

final class Semantic\Quantity
{
    #[Validate]
    public function validate(int $quantity): void
    {
        if ($quantity < 1 || $quantity > 99) {
            throw new Exception\InvalidQuantityException();
        }
    }
}

// ═══════════════════════════════════════════════════════════════════════════
// BEING - Intermediate states (Heidegger's Dasein - Becoming)
// ═══════════════════════════════════════════════════════════════════════════

// Each Being represents a step in the pipeline - clean, testable, focused

final readonly class Being\Inventory\StockLocated
{
    public function __construct(
        #[Input] public string $productId,
        #[Inject] Reason\WarehouseLocator $locator,
    ) {
        $this->warehouseId = $locator->locate($productId);
    }
}

final readonly class Being\Payment\CardValidated
{
    public function __construct(
        #[Input] public string $cardNumber,
        #[Input] public string $cardExpiry,
        #[Inject] Reason\CardValidator $validator,
    ) {
        $validator->validate($cardNumber, $cardExpiry);
    }
}

// ... other Being classes follow the same pattern

// ═══════════════════════════════════════════════════════════════════════════
// MOMENT - Partial completions (Hegel's Moment - 契機)
// ═══════════════════════════════════════════════════════════════════════════

// Each Moment represents a completed sub-pipeline, ready for convergence

final readonly class Moment\InventoryReserved
{
    public string $reservationId;

    public function __construct(
        #[Input] public string $productId,
        #[Input] public int $quantity,
        #[Input] public string $warehouseId,
        #[Inject] Reason\InventoryReserver $reserver,
    ) {
        $this->reservationId = $reserver->reserve($warehouseId, $productId, $quantity);
    }
}

final readonly class Moment\PaymentCompleted
{
    public function __construct(
        #[Input] public string $authorizationCode,
        #[Input] public int $amount,
        #[Inject] Reason\PaymentGateway $gateway,
    ) {
        $gateway->capture($authorizationCode, $amount);
    }
}

final readonly class Moment\ShippingArranged
{
    public string $trackingNumber;
    public int $rate;

    public function __construct(
        #[Input] public string $carrierId,
        #[Input] public string $address,
        #[Inject] Reason\ShippingArranger $arranger,
    ) {
        $result = $arranger->arrange($carrierId, $address);
        $this->trackingNumber = $result['trackingNumber'];
        $this->rate = $result['rate'];
    }
}

// ═══════════════════════════════════════════════════════════════════════════
// FINAL - The convergence point (Aristotle's Energeia - Actuality)
// ═══════════════════════════════════════════════════════════════════════════

// All three Moments converge here - the diamond completes

final readonly class Final\OrderConfirmed
{
    public string $orderId;
    public string $status = 'confirmed';

    public function __construct(
        #[Moment] public Moment\InventoryReserved $inventory,
        #[Moment] public Moment\PaymentCompleted $payment,
        #[Moment] public Moment\ShippingArranged $shipping,
    ) {
        $this->orderId = 'ORD-' . date('Ymd') . '-' . bin2hex(random_bytes(4));
    }
}

// ═══════════════════════════════════════════════════════════════════════════
// USAGE - Simple, declarative invocation
// ═══════════════════════════════════════════════════════════════════════════

$input = new Input\OrderInput(
    cartId: 'CART-001',
    customerId: 'CUST-001',
    productId: 'PROD-001',
    quantity: 2,
    cardNumber: '4111111111111111',
    cardExpiry: '12/25',
    cardCvv: '123',
    postalCode: '150-0001',
    streetAddress: '渋谷区神宮前1-1-1',
    amount: 10000,
);

// One line to trigger the entire diamond metamorphosis
$order = $becoming($input);

// $order is now a fully confirmed OrderConfirmed with all Moments resolved

// ═══════════════════════════════════════════════════════════════════════════
// BENEFITS OF THIS APPROACH:
// ═══════════════════════════════════════════════════════════════════════════
//
// 1. VALIDATION ENCAPSULATED
//    - Each Semantic class owns its validation
//    - Reusable across any Input that needs it
//    - Multilingual error messages via #[Message] attribute
//
// 2. DECLARATIVE STATE FLOW
//    - No manual state tracking
//    - The type system enforces valid transitions
//    - Impossible to reach Final without all Moments
//
// 3. AUTOMATIC TRANSACTION HANDLING
//    - Framework manages rollback on failure
//    - No nested try-catch required
//    - Consistent state guaranteed
//
// 4. PERFECT SEPARATION OF CONCERNS
//    - Input: what the user provides
//    - Semantic: what the values mean
//    - Being: how transformation progresses
//    - Moment: what must converge
//    - Final: the completed state
//    - Reason: why things happen (business logic)
//
// 5. TESTABILITY
//    - Each class testable in isolation
//    - Integration test: just test Input → Final
//    - No mocking of half the universe
//
// 6. EXTENSION IS TRIVIAL
//    - Adding a 4th pipeline (e.g., FraudChecked Moment):
//      - Create Being\Fraud\* classes
//      - Create Moment\FraudCleared class
//      - Add #[Moment] to OrderConfirmed constructor
//      - Done. No changes to existing code.
//
// LINES OF CODE: ~150 (spread across focused classes)
// CYCLOMATIC COMPLEXITY: Very low (each class does one thing)
// TEST COMBINATIONS: Linear growth (test each Being/Moment independently)
//
// ═══════════════════════════════════════════════════════════════════════════
// PHILOSOPHICAL FOUNDATION:
// ═══════════════════════════════════════════════════════════════════════════
//
// Input    → Aristotle's Dynamis (δύναμις) - Potentiality
// Being    → Heidegger's Dasein - Being-in-becoming
// Moment   → Hegel's Moment (契機) - Essential aspect of the whole
// Final    → Aristotle's Energeia (ἐνέργεια) - Actuality
// Semantic → Frege's Sinn + 言霊 - Meaning with power
// Reason   → Leibniz's Sufficient Reason - Why it exists
//
// The framework development itself embodies Sartre:
// "L'existence précède l'essence" - Existence precedes essence
// The framework was built first; the philosophy was discovered after.
