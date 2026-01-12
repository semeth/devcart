<?php

namespace App\Extensions\Shipping;

use App\Extensions\Base\BaseExtension;
use App\Extensions\Base\ShippingExtensionInterface;

/**
 * Free Shipping Extension
 * 
 * Provides free shipping when minimum order amount is met (dummy implementation - ready for development)
 */
class FreeShipping extends BaseExtension implements ShippingExtensionInterface
{
    public function getType(): string
    {
        return 'shipping';
    }

    public function getCode(): string
    {
        return 'free_shipping';
    }

    public function getName(): string
    {
        return 'Free Shipping';
    }

    public function getDescription(): string
    {
        return 'Free shipping when order meets minimum amount (dummy implementation)';
    }

    public function getVersion(): string
    {
        return '1.0.0';
    }

    /**
     * Calculate shipping cost
     */
    public function calculateShipping(array $cartData, array $addressData): float
    {
        // Dummy implementation
        $minimumOrder = (float) $this->getSetting('minimum_order', 50.00);
        $subtotal = $cartData['subtotal'] ?? 0;
        
        // Free shipping if minimum order amount is met
        if ($subtotal >= $minimumOrder) {
            return 0.00;
        }
        
        // Otherwise, return a standard rate (or disable this method)
        return (float) $this->getSetting('fallback_rate', 10.00);
    }

    /**
     * Get available shipping options
     */
    public function getAvailableOptions(array $cartData, array $addressData): array
    {
        $cost = $this->calculateShipping($cartData, $addressData);
        $minimumOrder = (float) $this->getSetting('minimum_order', 50.00);
        $subtotal = $cartData['subtotal'] ?? 0;
        
        $name = 'Free Shipping';
        if ($subtotal < $minimumOrder) {
            $name = 'Free Shipping (Add $' . number_format($minimumOrder - $subtotal, 2) . ' more)';
        }
        
        return [
            [
                'code' => 'free',
                'name' => $name,
                'cost' => $cost,
                'estimated_days' => (int) $this->getSetting('estimated_days', 5),
            ],
        ];
    }

    /**
     * Check if shipping method is available for given address
     */
    public function isAvailableForAddress(array $addressData): bool
    {
        // Dummy implementation
        return true;
    }

    /**
     * Get estimated delivery days
     */
    public function getEstimatedDeliveryDays(array $addressData): ?int
    {
        return (int) $this->getSetting('estimated_days', 5);
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
                'default' => false,
                'description' => 'Enable Free Shipping',
            ],
            [
                'key' => 'minimum_order',
                'label' => 'Minimum Order Amount',
                'type' => 'decimal',
                'required' => true,
                'default' => 50.00,
                'description' => 'Minimum order amount for free shipping',
                'min' => 0,
                'step' => 0.01,
            ],
            [
                'key' => 'fallback_rate',
                'label' => 'Fallback Rate',
                'type' => 'decimal',
                'required' => false,
                'default' => 10.00,
                'description' => 'Shipping rate if minimum order not met (or set to 0 to disable)',
                'min' => 0,
                'step' => 0.01,
            ],
            [
                'key' => 'estimated_days',
                'label' => 'Estimated Delivery Days',
                'type' => 'number',
                'required' => false,
                'default' => 5,
                'description' => 'Estimated delivery time in days',
                'min' => 1,
            ],
            [
                'key' => 'description',
                'label' => 'Description',
                'type' => 'textarea',
                'required' => false,
                'default' => 'Free shipping when order meets minimum amount',
                'description' => 'Display description for customers',
                'rows' => 3,
            ],
        ];
    }
}
