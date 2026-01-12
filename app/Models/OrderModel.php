<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table            = 'orders';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'order_number',
        'user_id',
        'email',
        'status',
        'payment_status',
        'payment_method',
        'subtotal',
        'tax_amount',
        'shipping_amount',
        'discount_amount',
        'total_amount',
        'currency',
        'billing_address_id',
        'shipping_address_id',
        'notes',
        'shipped_at',
        'delivered_at',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'order_number'        => 'required|max_length[50]|is_unique[orders.order_number,id,{id}]',
        'user_id'             => 'permit_empty|integer',
        'email'               => 'required|valid_email|max_length[255]',
        'status'              => 'permit_empty|in_list[pending,processing,shipped,delivered,cancelled,refunded]',
        'payment_status'      => 'permit_empty|in_list[pending,paid,failed,refunded]',
        'payment_method'      => 'permit_empty|max_length[50]',
        'subtotal'            => 'required|decimal',
        'tax_amount'          => 'permit_empty|decimal',
        'shipping_amount'     => 'permit_empty|decimal',
        'discount_amount'     => 'permit_empty|decimal',
        'total_amount'        => 'required|decimal',
        'currency'            => 'permit_empty|max_length[3]',
        'billing_address_id'   => 'permit_empty|integer',
        'shipping_address_id' => 'permit_empty|integer',
    ];

    protected $validationMessages = [];
    protected $skipValidation     = false;
    protected $cleanValidationRules = true;

    /**
     * Generate unique order number
     */
    public function generateOrderNumber(): string
    {
        do {
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -8));
        } while ($this->where('order_number', $orderNumber)->countAllResults() > 0);
        
        return $orderNumber;
    }

    /**
     * Find order by order number
     */
    public function findByOrderNumber(string $orderNumber)
    {
        return $this->where('order_number', $orderNumber)->first();
    }

    /**
     * Get orders by user
     */
    public function getByUserId(int $userId)
    {
        return $this->where('user_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get orders by status
     */
    public function getByStatus(string $status)
    {
        return $this->where('status', $status)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get orders by payment status
     */
    public function getByPaymentStatus(string $paymentStatus)
    {
        return $this->where('payment_status', $paymentStatus)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get order with items and addresses
     */
    public function getOrderDetails(int $orderId)
    {
        $order = $this->find($orderId);
        if (!$order) {
            return null;
        }

        // Get order items
        $orderItemModel = new OrderItemModel();
        $order['items'] = $orderItemModel->getByOrderId($orderId);

        // Get addresses
        $addressModel = new AddressModel();
        if ($order['billing_address_id']) {
            $order['billing_address'] = $addressModel->find($order['billing_address_id']);
        }
        if ($order['shipping_address_id']) {
            $order['shipping_address'] = $addressModel->find($order['shipping_address_id']);
        }

        // Get payments
        $paymentModel = new PaymentModel();
        $order['payments'] = $paymentModel->getByOrderId($orderId);

        return $order;
    }

    /**
     * Create order from cart
     */
    public function createFromCart(array $cartData, $userId = null, $billingAddressId = null, $shippingAddressId = null)
    {
        $orderNumber = $this->generateOrderNumber();
        
        $orderData = [
            'order_number' => $orderNumber,
            'user_id'      => $userId,
            'email'        => $cartData['email'] ?? null,
            'status'       => 'pending',
            'payment_status' => 'pending',
            'subtotal'     => $cartData['subtotal'] ?? 0,
            'tax_amount'   => $cartData['tax_amount'] ?? 0,
            'shipping_amount' => $cartData['shipping_amount'] ?? 0,
            'discount_amount' => $cartData['discount_amount'] ?? 0,
            'total_amount' => $cartData['total_amount'] ?? 0,
            'currency'     => $cartData['currency'] ?? 'USD',
            'billing_address_id' => $billingAddressId,
            'shipping_address_id' => $shippingAddressId,
        ];

        $orderId = $this->insert($orderData);
        
        if ($orderId && isset($cartData['items'])) {
            $orderItemModel = new OrderItemModel();
            foreach ($cartData['items'] as $item) {
                $orderItemModel->insert([
                    'order_id'     => $orderId,
                    'product_id'   => $item['product_id'],
                    'product_name' => $item['product_name'],
                    'product_sku'  => $item['product_sku'] ?? null,
                    'quantity'    => $item['quantity'],
                    'price'       => $item['price'],
                    'subtotal'    => $item['price'] * $item['quantity'],
                ]);
            }
        }

        return $orderId;
    }

    /**
     * Update order status
     */
    public function updateStatus(int $orderId, string $status)
    {
        $updateData = ['status' => $status];
        
        if ($status === 'shipped') {
            $updateData['shipped_at'] = date('Y-m-d H:i:s');
        } elseif ($status === 'delivered') {
            $updateData['delivered_at'] = date('Y-m-d H:i:s');
        }
        
        return $this->update($orderId, $updateData);
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus(int $orderId, string $paymentStatus)
    {
        return $this->update($orderId, ['payment_status' => $paymentStatus]);
    }

    /**
     * Calculate order totals
     */
    public function calculateTotals(float $subtotal, float $taxAmount = 0, float $shippingAmount = 0, float $discountAmount = 0): array
    {
        $totalAmount = $subtotal + $taxAmount + $shippingAmount - $discountAmount;
        
        return [
            'subtotal'       => $subtotal,
            'tax_amount'     => $taxAmount,
            'shipping_amount' => $shippingAmount,
            'discount_amount' => $discountAmount,
            'total_amount'   => max(0, $totalAmount), // Ensure total is not negative
        ];
    }
}
