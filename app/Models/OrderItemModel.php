<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderItemModel extends Model
{
    protected $table            = 'order_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'order_id',
        'product_id',
        'product_name',
        'product_sku',
        'quantity',
        'price',
        'subtotal',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = null;

    // Validation
    protected $validationRules = [
        'order_id'     => 'required|integer',
        'product_id'   => 'permit_empty|integer',
        'product_name' => 'required|max_length[255]',
        'product_sku'  => 'permit_empty|max_length[100]',
        'quantity'     => 'required|integer|greater_than[0]',
        'price'        => 'required|decimal',
        'subtotal'     => 'required|decimal',
    ];

    protected $validationMessages = [];
    protected $skipValidation     = false;
    protected $cleanValidationRules = true;

    /**
     * Get order items by order ID
     */
    public function getByOrderId(int $orderId)
    {
        return $this->where('order_id', $orderId)->findAll();
    }

    /**
     * Get order items with product details (if product still exists)
     */
    public function getWithProducts(int $orderId)
    {
        $items = $this->getByOrderId($orderId);
        
        foreach ($items as &$item) {
            if ($item['product_id']) {
                $productModel = new ProductModel();
                $item['product'] = $productModel->find($item['product_id']);
            }
        }
        
        return $items;
    }

    /**
     * Get order items by product ID (for analytics)
     */
    public function getByProductId(int $productId)
    {
        return $this->where('product_id', $productId)->findAll();
    }

    /**
     * Calculate order item subtotal
     */
    public function calculateSubtotal(float $price, int $quantity): float
    {
        return $price * $quantity;
    }

    /**
     * Get total quantity sold for a product
     */
    public function getTotalQuantitySold(int $productId): int
    {
        $result = $this->selectSum('quantity')
                       ->where('product_id', $productId)
                       ->first();
        
        return (int) ($result['quantity'] ?? 0);
    }

    /**
     * Get total revenue for a product
     */
    public function getTotalRevenue(int $productId): float
    {
        $result = $this->selectSum('subtotal')
                       ->where('product_id', $productId)
                       ->first();
        
        return (float) ($result['subtotal'] ?? 0);
    }
}
