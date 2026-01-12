<?php

namespace App\Extensions\Payment;

use App\Extensions\Base\BaseExtension;
use App\Extensions\Base\PaymentExtensionInterface;

/**
 * Cash on Delivery Payment Extension
 * 
 * Allows customers to pay when they receive their order
 */
class CashOnDelivery extends BaseExtension implements PaymentExtensionInterface
{
    public function getType(): string
    {
        return 'payment';
    }

    public function getCode(): string
    {
        return 'cash_on_delivery';
    }

    public function getName(): string
    {
        return 'Cash on Delivery';
    }

    public function getDescription(): string
    {
        return 'Pay when you receive your order. No upfront payment required.';
    }

    public function getVersion(): string
    {
        return '1.0.0';
    }

    /**
     * Process payment for an order
     * 
     * For Cash on Delivery, payment is always pending until delivery
     */
    public function processPayment(array $orderData): array
    {
        // COD fee (if configured)
        $fee = (float) $this->getSetting('fee', 0);
        
        return [
            'success' => true,
            'payment_status' => 'pending',
            'transaction_id' => null,
            'message' => 'Payment will be collected on delivery',
            'fee' => $fee,
        ];
    }

    /**
     * Get required fields for checkout form
     */
    public function getRequiredFields(): array
    {
        return [];
    }

    /**
     * Check if payment method requires additional form fields
     */
    public function requiresAdditionalFields(): bool
    {
        return false;
    }

    /**
     * Get payment status after processing
     */
    public function getPaymentStatus(?string $transactionId = null): string
    {
        return 'pending';
    }

    /**
     * Get settings schema for admin configuration
     */
    public function getSettingsSchema(): array
    {
        return [
            [
                'key' => 'enabled',
                'label' => 'Enabled',
                'type' => 'boolean',
                'required' => false,
                'default' => true,
                'description' => 'Enable Cash on Delivery payment method',
            ],
            [
                'key' => 'fee',
                'label' => 'COD Fee',
                'type' => 'decimal',
                'required' => false,
                'default' => 0,
                'description' => 'Optional fee for cash on delivery (0 for no fee)',
                'min' => 0,
                'step' => 0.01,
            ],
            [
                'key' => 'description',
                'label' => 'Description',
                'type' => 'textarea',
                'required' => false,
                'default' => 'Pay when you receive your order. No upfront payment required.',
                'description' => 'Display description for customers',
                'rows' => 3,
            ],
        ];
    }

    /**
     * Override to specify encrypted keys (none for COD)
     */
    protected function getEncryptedSettingKeys(): array
    {
        return []; // No sensitive data for COD
    }
}
