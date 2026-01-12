<?php

namespace App\Extensions\Base;

/**
 * Interface for payment extensions
 * 
 * Payment extensions handle payment processing for orders
 */
interface PaymentExtensionInterface extends ExtensionInterface
{
    /**
     * Process payment for an order
     * 
     * @param array $orderData Order data including:
     *   - order_id: int
     *   - amount: float
     *   - currency: string
     *   - customer_email: string
     *   - billing_address: array
     *   - [additional fields as needed]
     * @return array Result array with:
     *   - success: bool
     *   - payment_status: string (pending, paid, failed)
     *   - transaction_id: string|null
     *   - message: string
     *   - [additional data]
     */
    public function processPayment(array $orderData): array;

    /**
     * Get required fields for checkout form
     * Returns array of field definitions needed in checkout
     * 
     * @return array Array of field definitions
     */
    public function getRequiredFields(): array;

    /**
     * Check if payment method requires additional form fields
     * 
     * @return bool
     */
    public function requiresAdditionalFields(): bool;

    /**
     * Get payment status after processing
     * 
     * @param string|null $transactionId
     * @return string Payment status (pending, paid, failed)
     */
    public function getPaymentStatus(?string $transactionId = null): string;
}
