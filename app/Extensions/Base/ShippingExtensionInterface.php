<?php

namespace App\Extensions\Base;

/**
 * Interface for shipping extensions
 * 
 * Shipping extensions calculate shipping costs and provide shipping options
 */
interface ShippingExtensionInterface extends ExtensionInterface
{
    /**
     * Calculate shipping cost
     * 
     * @param array $cartData Cart data including:
     *   - items: array of cart items with weight, quantity, etc.
     *   - subtotal: float
     *   - [additional fields]
     * @param array $addressData Shipping address data:
     *   - country: string
     *   - state: string
     *   - postal_code: string
     *   - city: string
     *   - [additional fields]
     * @return float Shipping cost
     */
    public function calculateShipping(array $cartData, array $addressData): float;

    /**
     * Get available shipping options
     * Returns array of shipping options with rates
     * 
     * @param array $cartData Cart data
     * @param array $addressData Shipping address data
     * @return array Array of options:
     *   [
     *     [
     *       'code' => 'standard',
     *       'name' => 'Standard Shipping',
     *       'cost' => 10.00,
     *       'estimated_days' => 5
     *     ],
     *     ...
     *   ]
     */
    public function getAvailableOptions(array $cartData, array $addressData): array;

    /**
     * Check if shipping method is available for given address
     * 
     * @param array $addressData Shipping address data
     * @return bool
     */
    public function isAvailableForAddress(array $addressData): bool;

    /**
     * Get estimated delivery days
     * 
     * @param array $addressData Shipping address data
     * @return int|null Number of days, or null if not available
     */
    public function getEstimatedDeliveryDays(array $addressData): ?int;
}
