<?php

namespace App\Extensions\Shipping;

use App\Extensions\Base\BaseExtension;
use App\Extensions\Base\ShippingExtensionInterface;

/**
 * Flat Rate Shipping Extension
 * 
 * Provides fixed shipping rate (dummy implementation - ready for development)
 */
class FlatRate extends BaseExtension implements ShippingExtensionInterface
{
    public function getType(): string
    {
        return 'shipping';
    }

    public function getCode(): string
    {
        return 'flat_rate';
    }

    public function getName(): string
    {
        return 'Flat Rate Shipping';
    }

    public function getDescription(): string
    {
        return 'Fixed shipping rate for all orders (dummy implementation)';
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
        // Dummy implementation - returns configured flat rate
        $rate = (float) $this->getSetting('rate', 10.00);
        return $rate;
    }

    /**
     * Get available shipping options
     */
    public function getAvailableOptions(array $cartData, array $addressData): array
    {
        $rate = $this->calculateShipping($cartData, $addressData);
        
        return [
            [
                'code' => 'standard',
                'name' => 'Standard Shipping',
                'cost' => $rate,
                'estimated_days' => (int) $this->getSetting('estimated_days', 5),
            ],
        ];
    }

    /**
     * Check if shipping method is available for given address
     */
    public function isAvailableForAddress(array $addressData): bool
    {
        // Dummy implementation - available for all addresses
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
                'description' => 'Enable Flat Rate Shipping',
            ],
            [
                'key' => 'rate',
                'label' => 'Shipping Rate',
                'type' => 'decimal',
                'required' => true,
                'default' => 10.00,
                'description' => 'Fixed shipping rate amount',
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
                'default' => 'Fixed shipping rate for all orders',
                'description' => 'Display description for customers',
                'rows' => 3,
            ],
        ];
    }
}
