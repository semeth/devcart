<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    protected $table            = 'payments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'order_id',
        'transaction_id',
        'payment_method',
        'amount',
        'currency',
        'status',
        'gateway_response',
        'paid_at',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = null;

    // Validation
    protected $validationRules = [
        'order_id'       => 'required|integer',
        'transaction_id' => 'permit_empty|max_length[255]',
        'payment_method' => 'required|max_length[50]',
        'amount'         => 'required|decimal',
        'currency'       => 'permit_empty|max_length[3]',
        'status'         => 'permit_empty|in_list[pending,completed,failed,refunded,cancelled]',
    ];

    protected $validationMessages = [];
    protected $skipValidation     = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['processGatewayResponse'];
    protected $beforeUpdate   = ['processGatewayResponse'];

    /**
     * Process gateway response JSON before insert/update
     */
    protected function processGatewayResponse(array $data)
    {
        if (isset($data['data']['gateway_response']) && is_array($data['data']['gateway_response'])) {
            $data['data']['gateway_response'] = json_encode($data['data']['gateway_response']);
        }
        return $data;
    }

    /**
     * Get payments by order ID
     */
    public function getByOrderId(int $orderId)
    {
        $payments = $this->where('order_id', $orderId)
                         ->orderBy('created_at', 'DESC')
                         ->findAll();
        
        foreach ($payments as &$payment) {
            if ($payment['gateway_response']) {
                $payment['gateway_response'] = json_decode($payment['gateway_response'], true) ?? [];
            }
        }
        
        return $payments;
    }

    /**
     * Find payment by transaction ID
     */
    public function findByTransactionId(string $transactionId)
    {
        $payment = $this->where('transaction_id', $transactionId)->first();
        
        if ($payment && $payment['gateway_response']) {
            $payment['gateway_response'] = json_decode($payment['gateway_response'], true) ?? [];
        }
        
        return $payment;
    }

    /**
     * Get payments by status
     */
    public function getByStatus(string $status)
    {
        return $this->where('status', $status)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Create payment record
     */
    public function createPayment(int $orderId, string $paymentMethod, float $amount, string $currency = 'USD', string $transactionId = null, array $gatewayResponse = null)
    {
        $data = [
            'order_id'       => $orderId,
            'transaction_id' => $transactionId,
            'payment_method' => $paymentMethod,
            'amount'         => $amount,
            'currency'       => $currency,
            'status'         => 'pending',
            'gateway_response' => $gatewayResponse,
        ];

        return $this->insert($data);
    }

    /**
     * Mark payment as completed
     */
    public function markAsCompleted(int $paymentId, string $transactionId = null)
    {
        $updateData = [
            'status' => 'completed',
            'paid_at' => date('Y-m-d H:i:s'),
        ];
        
        if ($transactionId) {
            $updateData['transaction_id'] = $transactionId;
        }
        
        $result = $this->update($paymentId, $updateData);
        
        // Update order payment status
        if ($result) {
            $payment = $this->find($paymentId);
            if ($payment) {
                $orderModel = new OrderModel();
                $orderModel->updatePaymentStatus($payment['order_id'], 'paid');
            }
        }
        
        return $result;
    }

    /**
     * Mark payment as failed
     */
    public function markAsFailed(int $paymentId, array $gatewayResponse = null)
    {
        $updateData = [
            'status' => 'failed',
        ];
        
        if ($gatewayResponse) {
            $updateData['gateway_response'] = $gatewayResponse;
        }
        
        $result = $this->update($paymentId, $updateData);
        
        // Update order payment status
        if ($result) {
            $payment = $this->find($paymentId);
            if ($payment) {
                $orderModel = new OrderModel();
                $orderModel->updatePaymentStatus($payment['order_id'], 'failed');
            }
        }
        
        return $result;
    }

    /**
     * Process refund
     */
    public function processRefund(int $paymentId, array $gatewayResponse = null)
    {
        $updateData = [
            'status' => 'refunded',
        ];
        
        if ($gatewayResponse) {
            $updateData['gateway_response'] = $gatewayResponse;
        }
        
        $result = $this->update($paymentId, $updateData);
        
        // Update order payment status
        if ($result) {
            $payment = $this->find($paymentId);
            if ($payment) {
                $orderModel = new OrderModel();
                $orderModel->updatePaymentStatus($payment['order_id'], 'refunded');
                $orderModel->updateStatus($payment['order_id'], 'refunded');
            }
        }
        
        return $result;
    }

    /**
     * Get total revenue by payment method
     */
    public function getRevenueByMethod(string $paymentMethod): float
    {
        $result = $this->selectSum('amount')
                       ->where('payment_method', $paymentMethod)
                       ->where('status', 'completed')
                       ->first();
        
        return (float) ($result['amount'] ?? 0);
    }

    /**
     * Get total revenue for a date range
     */
    public function getRevenueByDateRange(string $startDate, string $endDate): float
    {
        $result = $this->selectSum('amount')
                       ->where('status', 'completed')
                       ->where('paid_at >=', $startDate)
                       ->where('paid_at <=', $endDate)
                       ->first();
        
        return (float) ($result['amount'] ?? 0);
    }
}
