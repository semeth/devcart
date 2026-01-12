<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Payment extends BaseConfig
{
    /**
     * Payment gateway provider
     * Options: 'stripe', 'paypal', 'manual'
     */
    public string $gateway = 'stripe';

    /**
     * Stripe Configuration
     */
    public string $stripePublishableKey = '';
    public string $stripeSecretKey = '';
    public string $stripeWebhookSecret = '';

    /**
     * Currency
     */
    public string $currency = 'USD';

    /**
     * Payment methods available
     */
    public array $paymentMethods = [
        'stripe' => 'Credit/Debit Card (Stripe)',
        'manual' => 'Manual Payment',
    ];

    /**
     * Whether to use test mode
     */
    public bool $testMode = true;
}
