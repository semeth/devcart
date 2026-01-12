<?php

namespace App\Models;

use CodeIgniter\Model;

class CartItemModel extends Model
{
    protected $table            = 'cart_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'session_id',
        'product_id',
        'quantity',
        'price',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = null;

    // Validation
    protected $validationRules = [
        'user_id'    => 'permit_empty|integer',
        'session_id' => 'permit_empty|max_length[128]',
        'product_id' => 'required|integer',
        'quantity'   => 'required|integer|greater_than[0]',
        'price'      => 'required|decimal',
    ];

    protected $validationMessages = [];
    protected $skipValidation     = false;
    protected $cleanValidationRules = true;

    /**
     * Get cart items for a user
     */
    public function getByUserId(int $userId)
    {
        return $this->where('user_id', $userId)
                    ->findAll();
    }

    /**
     * Get cart items for a session (guest)
     */
    public function getBySessionId(string $sessionId)
    {
        return $this->where('session_id', $sessionId)
                    ->where('user_id', null)
                    ->findAll();
    }

    /**
     * Get cart items with product details
     */
    public function getCartWithProducts($userId = null, $sessionId = null)
    {
        $builder = $this->db->table('cart_items');
        $builder->select('cart_items.*, products.name, products.slug, products.sku, products.image, products.stock_quantity, products.stock_status');
        $builder->join('products', 'products.id = cart_items.product_id');
        
        if ($userId) {
            $builder->where('cart_items.user_id', $userId);
        } elseif ($sessionId) {
            $builder->where('cart_items.session_id', $sessionId);
            $builder->where('cart_items.user_id', null);
        } else {
            // Return empty array if no user or session
            return [];
        }
        
        return $builder->get()->getResultArray();
    }

    /**
     * Add item to cart
     */
    public function addToCart(int $productId, int $quantity, $userId = null, $sessionId = null, float $price = null)
    {
        // Get product price if not provided
        if ($price === null) {
            $productModel = new ProductModel();
            $product = $productModel->find($productId);
            if (!$product) {
                return false;
            }
            $price = $product['price'];
        }

        // Check if item already exists in cart
        $existingItem = null;
        if ($userId) {
            $existingItem = $this->where('user_id', $userId)
                                ->where('product_id', $productId)
                                ->first();
        } elseif ($sessionId) {
            $existingItem = $this->where('session_id', $sessionId)
                                ->where('user_id', null)
                                ->where('product_id', $productId)
                                ->first();
        }

        if ($existingItem) {
            // Update quantity
            return $this->update($existingItem['id'], [
                'quantity' => $existingItem['quantity'] + $quantity,
            ]);
        } else {
            // Create new cart item
            $data = [
                'product_id' => $productId,
                'quantity'  => $quantity,
                'price'     => $price,
            ];
            
            if ($userId) {
                $data['user_id'] = $userId;
            } elseif ($sessionId) {
                $data['session_id'] = $sessionId;
            }
            
            return $this->insert($data);
        }
    }

    /**
     * Update cart item quantity
     */
    public function updateQuantity(int $cartItemId, int $quantity)
    {
        if ($quantity <= 0) {
            return $this->delete($cartItemId);
        }
        
        return $this->update($cartItemId, ['quantity' => $quantity]);
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart(int $cartItemId)
    {
        return $this->delete($cartItemId);
    }

    /**
     * Clear cart for user or session
     */
    public function clearCart($userId = null, $sessionId = null)
    {
        if ($userId) {
            return $this->where('user_id', $userId)->delete();
        } elseif ($sessionId) {
            return $this->where('session_id', $sessionId)
                        ->where('user_id', null)
                        ->delete();
        }
        return false;
    }

    /**
     * Get cart total
     */
    public function getCartTotal($userId = null, $sessionId = null): float
    {
        $items = $userId ? $this->getByUserId($userId) : $this->getBySessionId($sessionId);
        $total = 0;
        
        foreach ($items as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        return $total;
    }

    /**
     * Get cart item count
     */
    public function getCartItemCount($userId = null, $sessionId = null): int
    {
        if ($userId) {
            return $this->where('user_id', $userId)->countAllResults();
        } elseif ($sessionId) {
            return $this->where('session_id', $sessionId)
                        ->where('user_id', null)
                        ->countAllResults();
        }
        return 0;
    }

    /**
     * Transfer cart from session to user (when guest logs in)
     */
    public function transferCartToUser(string $sessionId, int $userId)
    {
        $sessionItems = $this->getBySessionId($sessionId);
        
        foreach ($sessionItems as $item) {
            // Check if user already has this product in cart
            $existingItem = $this->where('user_id', $userId)
                                ->where('product_id', $item['product_id'])
                                ->first();
            
            if ($existingItem) {
                // Merge quantities
                $this->update($existingItem['id'], [
                    'quantity' => $existingItem['quantity'] + $item['quantity'],
                ]);
                // Delete session item
                $this->delete($item['id']);
            } else {
                // Transfer to user
                $this->update($item['id'], [
                    'user_id' => $userId,
                    'session_id' => null,
                ]);
            }
        }
        
        return true;
    }
}
