<?php

/**
 * Before: Traditional Order Processing (Spaghetti Code)
 *
 * This is how order processing typically looks without Be Framework.
 * Notice the deep nesting, scattered error handling, and difficult-to-follow flow.
 */

declare(strict_types=1);

namespace Traditional;

class OrderService
{
    public function __construct(
        private InventoryService $inventory,
        private PaymentService $payment,
        private ShippingService $shipping,
        private LoggerInterface $logger,
    ) {}

    /**
     * Process an order - the spaghetti begins here
     *
     * @throws OrderException
     */
    public function processOrder(array $orderData): array
    {
        $errors = [];

        // ═══════════════════════════════════════════════════════════════
        // VALIDATION HELL - scattered across the method
        // ═══════════════════════════════════════════════════════════════

        if (empty($orderData['cart_id'])) {
            $errors[] = 'Cart ID is required';
        }
        if (empty($orderData['customer_id'])) {
            $errors[] = 'Customer ID is required';
        }

        // Card validation - mixed with business logic
        if (empty($orderData['card_number'])) {
            $errors[] = 'Card number is required';
        } elseif (!$this->validateLuhn($orderData['card_number'])) {
            $errors[] = 'Invalid card number';
        }

        // Address validation - more conditions
        if (empty($orderData['postal_code'])) {
            $errors[] = 'Postal code is required';
        } elseif (!preg_match('/^\d{3}-?\d{4}$/', $orderData['postal_code'])) {
            $errors[] = 'Invalid postal code format';
        }

        if (empty($orderData['street_address'])) {
            $errors[] = 'Street address is required';
        }

        // Quantity validation
        if (!isset($orderData['quantity']) || $orderData['quantity'] < 1) {
            $errors[] = 'Quantity must be at least 1';
        } elseif ($orderData['quantity'] > 99) {
            $errors[] = 'Quantity cannot exceed 99';
        }

        if (!empty($errors)) {
            throw new ValidationException(implode(', ', $errors));
        }

        // ═══════════════════════════════════════════════════════════════
        // INVENTORY PIPELINE - manual state tracking begins
        // ═══════════════════════════════════════════════════════════════

        $inventoryReserved = false;
        $reservationId = null;
        $warehouseId = null;

        try {
            // Step 1: Locate stock
            $warehouse = $this->inventory->locateStock($orderData['product_id']);
            if (!$warehouse) {
                throw new InventoryException('Product not found in any warehouse');
            }
            $warehouseId = $warehouse['id'];
            $this->logger->info('Stock located', ['warehouse' => $warehouseId]);

            // Step 2: Check quantity
            $available = $this->inventory->checkQuantity(
                $warehouseId,
                $orderData['product_id'],
                $orderData['quantity']
            );
            if (!$available) {
                throw new InventoryException('Insufficient stock');
            }
            $this->logger->info('Quantity available', ['quantity' => $orderData['quantity']]);

            // Step 3: Reserve inventory
            $reservationId = $this->inventory->reserve(
                $warehouseId,
                $orderData['product_id'],
                $orderData['quantity']
            );
            $inventoryReserved = true;
            $this->logger->info('Inventory reserved', ['reservation' => $reservationId]);

        } catch (InventoryException $e) {
            $this->logger->error('Inventory failed', ['error' => $e->getMessage()]);
            throw new OrderException('Inventory check failed: ' . $e->getMessage());
        }

        // ═══════════════════════════════════════════════════════════════
        // PAYMENT PIPELINE - nested try-catch nightmare
        // ═══════════════════════════════════════════════════════════════

        $paymentCompleted = false;
        $authorizationCode = null;

        try {
            // Step 1: Validate card
            $cardValid = $this->payment->validateCard(
                $orderData['card_number'],
                $orderData['card_expiry'],
                $orderData['card_cvv']
            );
            if (!$cardValid) {
                throw new PaymentException('Card validation failed');
            }
            $this->logger->info('Card validated');

            // Step 2: Authorize payment
            $authResult = $this->payment->authorize(
                $orderData['card_number'],
                $orderData['amount']
            );
            if (!$authResult['success']) {
                throw new PaymentException('Authorization failed: ' . $authResult['message']);
            }
            $authorizationCode = $authResult['auth_code'];
            $this->logger->info('Payment authorized', ['auth_code' => $authorizationCode]);

            // Step 3: Capture payment
            $captureResult = $this->payment->capture($authorizationCode, $orderData['amount']);
            if (!$captureResult['success']) {
                // ROLLBACK: Void the authorization
                $this->payment->voidAuthorization($authorizationCode);
                throw new PaymentException('Capture failed: ' . $captureResult['message']);
            }
            $paymentCompleted = true;
            $this->logger->info('Payment captured');

        } catch (PaymentException $e) {
            // ROLLBACK: Release inventory reservation
            if ($inventoryReserved && $reservationId) {
                try {
                    $this->inventory->releaseReservation($reservationId);
                    $this->logger->info('Inventory reservation released due to payment failure');
                } catch (\Exception $releaseError) {
                    $this->logger->error('Failed to release reservation', [
                        'reservation' => $reservationId,
                        'error' => $releaseError->getMessage()
                    ]);
                    // Now we have an inconsistent state - inventory locked but payment failed
                }
            }
            throw new OrderException('Payment failed: ' . $e->getMessage());
        }

        // ═══════════════════════════════════════════════════════════════
        // SHIPPING PIPELINE - even more rollback complexity
        // ═══════════════════════════════════════════════════════════════

        $shippingArranged = false;
        $trackingNumber = null;
        $carrierId = null;

        try {
            // Step 1: Validate address
            $addressValid = $this->shipping->validateAddress(
                $orderData['postal_code'],
                $orderData['street_address']
            );
            if (!$addressValid) {
                throw new ShippingException('Invalid shipping address');
            }
            $this->logger->info('Address validated');

            // Step 2: Select carrier
            $carrier = $this->shipping->selectCarrier($orderData['postal_code']);
            if (!$carrier) {
                throw new ShippingException('No carrier available for this area');
            }
            $carrierId = $carrier['id'];
            $this->logger->info('Carrier selected', ['carrier' => $carrierId]);

            // Step 3: Arrange shipping
            $shippingResult = $this->shipping->arrange(
                $carrierId,
                $orderData['postal_code'],
                $orderData['street_address']
            );
            if (!$shippingResult['success']) {
                throw new ShippingException('Shipping arrangement failed');
            }
            $trackingNumber = $shippingResult['tracking_number'];
            $shippingArranged = true;
            $this->logger->info('Shipping arranged', ['tracking' => $trackingNumber]);

        } catch (ShippingException $e) {
            // ROLLBACK: Refund payment AND release inventory
            if ($paymentCompleted && $authorizationCode) {
                try {
                    $this->payment->refund($authorizationCode, $orderData['amount']);
                    $this->logger->info('Payment refunded due to shipping failure');
                } catch (\Exception $refundError) {
                    $this->logger->error('Failed to refund payment', [
                        'auth_code' => $authorizationCode,
                        'error' => $refundError->getMessage()
                    ]);
                    // Customer charged but no shipping - needs manual intervention
                }
            }

            if ($inventoryReserved && $reservationId) {
                try {
                    $this->inventory->releaseReservation($reservationId);
                    $this->logger->info('Inventory released due to shipping failure');
                } catch (\Exception $releaseError) {
                    $this->logger->error('Failed to release reservation', [
                        'reservation' => $reservationId,
                        'error' => $releaseError->getMessage()
                    ]);
                }
            }

            throw new OrderException('Shipping failed: ' . $e->getMessage());
        }

        // ═══════════════════════════════════════════════════════════════
        // FINALLY: Create order - after surviving all the above
        // ═══════════════════════════════════════════════════════════════

        $orderId = $this->generateOrderId();

        return [
            'order_id' => $orderId,
            'status' => 'confirmed',
            'inventory' => [
                'reservation_id' => $reservationId,
                'warehouse_id' => $warehouseId,
            ],
            'payment' => [
                'authorization_code' => $authorizationCode,
            ],
            'shipping' => [
                'carrier_id' => $carrierId,
                'tracking_number' => $trackingNumber,
            ],
        ];
    }

    private function validateLuhn(string $number): bool
    {
        $number = preg_replace('/\D/', '', $number);
        $sum = 0;
        $length = strlen($number);
        for ($i = 0; $i < $length; $i++) {
            $digit = (int) $number[$length - 1 - $i];
            if ($i % 2 === 1) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            $sum += $digit;
        }
        return $sum % 10 === 0;
    }

    private function generateOrderId(): string
    {
        return 'ORD-' . date('Ymd') . '-' . bin2hex(random_bytes(4));
    }
}

// ═══════════════════════════════════════════════════════════════════════════
// PROBLEMS WITH THIS APPROACH:
// ═══════════════════════════════════════════════════════════════════════════
//
// 1. VALIDATION SCATTERED
//    - Validation logic mixed with business logic
//    - Hard to reuse across different entry points
//    - Easy to forget validation in new code paths
//
// 2. MANUAL STATE TRACKING
//    - $inventoryReserved, $paymentCompleted, $shippingArranged flags
//    - Easy to get into inconsistent states
//    - Flag management is error-prone
//
// 3. ROLLBACK COMPLEXITY
//    - Each failure point needs to know about all previous successes
//    - Nested try-catch with manual rollback
//    - Rollback failures create worse problems (inconsistent state)
//
// 4. NO SEPARATION OF CONCERNS
//    - One massive method doing everything
//    - Hard to test individual parts
//    - Changes in one area affect the entire flow
//
// 5. ERROR HANDLING NIGHTMARE
//    - Different exception types need different handling
//    - Logging scattered throughout
//    - Error messages hard to internationalize
//
// 6. EXTENSION DIFFICULTY
//    - Adding a 4th pipeline (e.g., fraud check) requires:
//      - Adding new state flags
//      - Adding new try-catch blocks
//      - Updating ALL existing rollback logic
//      - Testing all failure combinations (exponential growth)
//
// LINES OF CODE: ~250 for a single use case
// CYCLOMATIC COMPLEXITY: Very high
// TEST COMBINATIONS: 2^3 = 8 minimum (success/fail for each pipeline)
//                    Actually more due to partial failures in each step
