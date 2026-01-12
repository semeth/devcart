<?php

namespace App\Extensions\Shipping;

use App\Extensions\Base\BaseExtension;
use App\Extensions\Base\ShippingExtensionInterface;

/**
 * Weight-Based Shipping Extension
 * 
 * Calculates shipping based on total weight (dummy implementation - ready for development)
 */
class WeightBased extends BaseExtension implements ShippingExtensionInterface
{
    public function getType(): string
    {
        return 'shipping';
    }

    public function getCode(): string
    {
        return 'weight_based';
    }

    public function getName(): string
    {
        return 'Weight-Based Shipping';
    }

    public function getDescription(): string
    {
        return 'Shipping cost calculated based on total weight (dummy implementation)';
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
        $baseRate = (float) $this->getSetting('base_rate', 5.00);
        $ratePerKg = (float) $this->getSetting('rate_per_kg', 2.00);
        
        // Calculate total weight (dummy - assume 1kg per item if weight not provided)
        $totalWeight = 0;
        if (isset($cartData['items'])) {
            foreach ($cartData['items'] as $item) {
                $weight = $item['weight'] ?? 1.0; // Default 1kg if weight not available
                $totalWeight += $weight * ($item['quantity'] ?? 1);
            }
        }
        
        return $baseRate + ($totalWeight * $ratePerKg);
    }

    /**
     * Get available shipping options
     */
    public function getAvailableOptions(array $cartData, array $addressData): array
    {
        $cost = $this->calculateShipping($cartData, $addressData);
        
        return [
            [
                'code' => 'weight_based',
                'name' => 'Weight-Based Shipping',
                'cost' => $cost,
                'estimated_days' => (int) $this->getSetting('estimated_days', 7),
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
        return (int) $this->getSetting('estimated_days', 7);
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
                'description' => 'Enable Weight-Based Shipping',
            ],
            [
                'key' => 'base_rate',
                'label' => 'Base Rate',
                'type' => 'decimal',
                'required' => true,
                'default' => 5.00,
                'description' => 'Base shipping rate',
                'min' => 0,
                'step' => 0.01,
            ],
            [
                'key' => 'rate_per_kg',
                'label' => 'Rate per Kilogram',
                'type' => 'decimal',
                'required' => true,
                'default' => 2.00,
                'description' => 'Shipping rate per kilogram',
                'min' => 0,
                'step' => 0.01,
            ],
            [
                'key' => 'estimated_days',
                'label' => 'Estimated Delivery Days',
                'type' => 'number',
                'required' => false,
                'default' => 7,
                'description' => 'Estimated delivery time in days',
                'min' => 1,
            ],
            [
                'key' => 'description',
                'label' => 'Description',
                'type' => 'textarea',
                'required' => false,
                'default' => 'Shipping cost calculated based on total weight',
                'description' => 'Display description for customers',
                'rows' => 3,
            ],
        ];
    }
}
