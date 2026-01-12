<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ExtensionsSeeder extends Seeder
{
    public function run()
    {
        $extensionModel = new \App\Models\ExtensionModel();
        $settingModel = new \App\Models\ExtensionSettingModel();

        // Cash on Delivery Payment Extension
        $codExtension = $extensionModel->findByTypeAndCode('payment', 'cash_on_delivery');
        if (!$codExtension) {
            $codId = $extensionModel->insert([
                'type' => 'payment',
                'code' => 'cash_on_delivery',
                'name' => 'Cash on Delivery',
                'description' => 'Pay when you receive your order. No upfront payment required.',
                'version' => '1.0.0',
                'is_active' => 1,
                'is_default' => 1,
                'sort_order' => 1,
            ]);

            // Default settings for COD
            $settingModel->setSetting($codId, 'enabled', '1', false);
            $settingModel->setSetting($codId, 'fee', '0', false);
            $settingModel->setSetting($codId, 'description', 'Pay when you receive your order. No upfront payment required.', false);
        }

        // Flat Rate Shipping Extension
        $flatRateExtension = $extensionModel->findByTypeAndCode('shipping', 'flat_rate');
        if (!$flatRateExtension) {
            $flatRateId = $extensionModel->insert([
                'type' => 'shipping',
                'code' => 'flat_rate',
                'name' => 'Flat Rate Shipping',
                'description' => 'Fixed shipping rate for all orders (dummy implementation)',
                'version' => '1.0.0',
                'is_active' => 0,
                'is_default' => 0,
                'sort_order' => 1,
            ]);

            // Default settings
            $settingModel->setSetting($flatRateId, 'enabled', '0', false);
            $settingModel->setSetting($flatRateId, 'rate', '10.00', false);
            $settingModel->setSetting($flatRateId, 'estimated_days', '5', false);
            $settingModel->setSetting($flatRateId, 'description', 'Fixed shipping rate for all orders', false);
        }

        // Weight-Based Shipping Extension
        $weightBasedExtension = $extensionModel->findByTypeAndCode('shipping', 'weight_based');
        if (!$weightBasedExtension) {
            $weightBasedId = $extensionModel->insert([
                'type' => 'shipping',
                'code' => 'weight_based',
                'name' => 'Weight-Based Shipping',
                'description' => 'Shipping cost calculated based on total weight (dummy implementation)',
                'version' => '1.0.0',
                'is_active' => 0,
                'is_default' => 0,
                'sort_order' => 2,
            ]);

            // Default settings
            $settingModel->setSetting($weightBasedId, 'enabled', '0', false);
            $settingModel->setSetting($weightBasedId, 'base_rate', '5.00', false);
            $settingModel->setSetting($weightBasedId, 'rate_per_kg', '2.00', false);
            $settingModel->setSetting($weightBasedId, 'estimated_days', '7', false);
            $settingModel->setSetting($weightBasedId, 'description', 'Shipping cost calculated based on total weight', false);
        }

        // Free Shipping Extension
        $freeShippingExtension = $extensionModel->findByTypeAndCode('shipping', 'free_shipping');
        if (!$freeShippingExtension) {
            $freeShippingId = $extensionModel->insert([
                'type' => 'shipping',
                'code' => 'free_shipping',
                'name' => 'Free Shipping',
                'description' => 'Free shipping when order meets minimum amount (dummy implementation)',
                'version' => '1.0.0',
                'is_active' => 0,
                'is_default' => 0,
                'sort_order' => 3,
            ]);

            // Default settings
            $settingModel->setSetting($freeShippingId, 'enabled', '0', false);
            $settingModel->setSetting($freeShippingId, 'minimum_order', '50.00', false);
            $settingModel->setSetting($freeShippingId, 'fallback_rate', '10.00', false);
            $settingModel->setSetting($freeShippingId, 'estimated_days', '5', false);
            $settingModel->setSetting($freeShippingId, 'description', 'Free shipping when order meets minimum amount', false);
        }
    }
}
