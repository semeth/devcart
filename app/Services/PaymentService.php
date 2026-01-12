<?php

namespace App\Services;

use App\Models\PaymentModel;
use App\Models\OrderModel;
use Config\Payment;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Exception\ApiErrorException;

class PaymentService
{
    protected $paymentConfig;
    protected $paymentModel;
    protected $orderModel;

    public function __construct()
    {
        $this->paymentConfig = config('Payment');
        $this->paymentModel = new PaymentModel();
        $this->orderModel = new OrderModel();

        // Initialize Stripe
        if ($this->paymentConfig->gateway === 'stripe') {
            Stripe::setApiKey($this->paymentConfig->stripeSecretKey);
        }
    }

    /**
     * Create a payment intent for Stripe
     *
     * @param float $amount Amount in dollars
     * @param string $currency Currency code (default: USD)
     * @param array $metadata Additional metadata
     * @return array Payment intent data
     */
    public function createPaymentIntent(float $amount, string $currency = 'USD', array $metadata = []): array
    {
        if ($this->paymentConfig->gateway !== 'stripe') {
            throw new \Exception('Stripe gateway is not configured');
        }

        try {
            $amountInCents = (int)($amount * 100); // Convert to cents

            $paymentIntent = PaymentIntent::create([
                'amount' => $amountInCents,
                'currency' => strtolower($currency),
                'metadata' => $metadata,
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            return [
                'success' => true,
                'client_secret' => $paymentIntent->client_secret,
                'payment_intent_id' => $paymentIntent->id,
                'data' => $paymentIntent->toArray(),
            ];
        } catch (ApiErrorException $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Confirm a payment intent
     *
     * @param string $paymentIntentId
     * @return array
     */
    public function confirmPaymentIntent(string $paymentIntentId): array
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
            
            return [
                'success' => true,
                'status' => $paymentIntent->status,
                'data' => $paymentIntent->toArray(),
            ];
        } catch (ApiErrorException $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Retrieve a payment intent
     *
     * @param string $paymentIntentId
     * @return array|null
     */
    public function getPaymentIntent(string $paymentIntentId): ?array
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
            return $paymentIntent->toArray();
        } catch (ApiErrorException $e) {
            return null;
        }
    }

    /**
     * Process payment for an order
     *
     * @param int $orderId
     * @param string $paymentMethod
     * @param float $amount
     * @param string $currency
     * @param array $paymentData Additional payment data (e.g., payment intent ID)
     * @return array
     */
    public function processPayment(int $orderId, string $paymentMethod, float $amount, string $currency = 'USD', array $paymentData = []): array
    {
        $order = $this->orderModel->find($orderId);
        if (!$order) {
            return [
                'success' => false,
                'error' => 'Order not found',
            ];
        }

        // Handle different payment methods
        switch ($paymentMethod) {
            case 'stripe':
                return $this->processStripePayment($orderId, $amount, $currency, $paymentData);
            
            case 'manual':
                return $this->processManualPayment($orderId, $amount, $currency);
            
            default:
                return [
                    'success' => false,
                    'error' => 'Invalid payment method',
                ];
        }
    }

    /**
     * Process Stripe payment
     */
    protected function processStripePayment(int $orderId, float $amount, string $currency, array $paymentData): array
    {
        $paymentIntentId = $paymentData['payment_intent_id'] ?? null;

        if (!$paymentIntentId) {
            return [
                'success' => false,
                'error' => 'Payment intent ID is required',
            ];
        }

        // Retrieve payment intent from Stripe
        $paymentIntent = $this->getPaymentIntent($paymentIntentId);
        
        if (!$paymentIntent) {
            return [
                'success' => false,
                'error' => 'Payment intent not found',
            ];
        }

        $status = $paymentIntent['status'];
        $transactionId = $paymentIntent['id'];

        // Create payment record
        $paymentId = $this->paymentModel->createPayment(
            $orderId,
            'stripe',
            $amount,
            $currency,
            $transactionId,
            $paymentIntent
        );

        if (!$paymentId) {
            return [
                'success' => false,
                'error' => 'Failed to create payment record',
            ];
        }

        // Update payment status based on Stripe status
        if ($status === 'succeeded') {
            $this->paymentModel->markAsCompleted($paymentId, $transactionId);
            $this->orderModel->updatePaymentStatus($orderId, 'paid');
            $this->orderModel->updateStatus($orderId, 'processing');
            
            return [
                'success' => true,
                'payment_id' => $paymentId,
                'status' => 'completed',
            ];
        } elseif ($status === 'requires_payment_method' || $status === 'canceled') {
            $this->paymentModel->markAsFailed($paymentId, $paymentIntent);
            $this->orderModel->updatePaymentStatus($orderId, 'failed');
            
            return [
                'success' => false,
                'error' => 'Payment failed or was canceled',
                'payment_id' => $paymentId,
            ];
        } else {
            // Payment is still processing
            return [
                'success' => true,
                'payment_id' => $paymentId,
                'status' => 'pending',
                'message' => 'Payment is being processed',
            ];
        }
    }

    /**
     * Process manual payment (e.g., bank transfer, cash on delivery)
     */
    protected function processManualPayment(int $orderId, float $amount, string $currency): array
    {
        $paymentId = $this->paymentModel->createPayment(
            $orderId,
            'manual',
            $amount,
            $currency,
            null,
            ['method' => 'manual']
        );

        if (!$paymentId) {
            return [
                'success' => false,
                'error' => 'Failed to create payment record',
            ];
        }

        // Manual payments are marked as pending
        $this->orderModel->updatePaymentStatus($orderId, 'pending');
        $this->orderModel->updateStatus($orderId, 'pending');

        return [
            'success' => true,
            'payment_id' => $paymentId,
            'status' => 'pending',
            'message' => 'Payment will be processed manually',
        ];
    }

    /**
     * Handle Stripe webhook event
     *
     * @param array $eventData
     * @return array
     */
    public function handleWebhook(array $eventData): array
    {
        $eventType = $eventData['type'] ?? '';
        $paymentIntent = $eventData['data']['object'] ?? null;

        if (!$paymentIntent || !isset($paymentIntent['id'])) {
            return [
                'success' => false,
                'error' => 'Invalid webhook data',
            ];
        }

        $transactionId = $paymentIntent['id'];
        $payment = $this->paymentModel->findByTransactionId($transactionId);

        if (!$payment) {
            return [
                'success' => false,
                'error' => 'Payment not found',
            ];
        }

        $orderId = $payment['order_id'];

        switch ($eventType) {
            case 'payment_intent.succeeded':
                $this->paymentModel->markAsCompleted($payment['id'], $transactionId);
                $this->orderModel->updatePaymentStatus($orderId, 'paid');
                $this->orderModel->updateStatus($orderId, 'processing');
                break;

            case 'payment_intent.payment_failed':
                $this->paymentModel->markAsFailed($payment['id'], $paymentIntent);
                $this->orderModel->updatePaymentStatus($orderId, 'failed');
                break;

            case 'payment_intent.canceled':
                $this->paymentModel->update($payment['id'], [
                    'status' => 'cancelled',
                ]);
                $this->orderModel->updatePaymentStatus($orderId, 'cancelled');
                break;
        }

        return [
            'success' => true,
            'event_type' => $eventType,
        ];
    }

    /**
     * Refund a payment
     *
     * @param int $paymentId
     * @param float|null $amount Amount to refund (null for full refund)
     * @return array
     */
    public function refundPayment(int $paymentId, ?float $amount = null): array
    {
        $payment = $this->paymentModel->find($paymentId);
        
        if (!$payment) {
            return [
                'success' => false,
                'error' => 'Payment not found',
            ];
        }

        if ($payment['payment_method'] !== 'stripe') {
            return [
                'success' => false,
                'error' => 'Refunds are only supported for Stripe payments',
            ];
        }

        if (!$payment['transaction_id']) {
            return [
                'success' => false,
                'error' => 'Transaction ID not found',
            ];
        }

        try {
            $refundAmount = $amount ? (int)($amount * 100) : null;
            
            $refund = \Stripe\Refund::create([
                'payment_intent' => $payment['transaction_id'],
                'amount' => $refundAmount,
            ]);

            $this->paymentModel->processRefund($paymentId, $refund->toArray());

            return [
                'success' => true,
                'refund_id' => $refund->id,
                'amount' => $refund->amount / 100,
            ];
        } catch (ApiErrorException $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
