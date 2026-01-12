<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table            = 'products';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'slug',
        'sku',
        'description',
        'short_description',
        'category_id',
        'price',
        'compare_at_price',
        'cost_price',
        'stock_quantity',
        'manage_stock',
        'stock_status',
        'weight',
        'dimensions',
        'image',
        'gallery',
        'is_active',
        'is_featured',
        'sort_order',
        'meta_title',
        'meta_description',
        'views_count',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'name'             => 'required|min_length[2]|max_length[255]',
        'slug'             => 'required|alpha_dash|max_length[255]|is_unique[products.slug,id,{id}]',
        'sku'              => 'required|alpha_numeric|max_length[100]|is_unique[products.sku,id,{id}]',
        'description'      => 'permit_empty',
        'short_description' => 'permit_empty|max_length[500]',
        'category_id'      => 'permit_empty|integer',
        'price'            => 'required|decimal',
        'compare_at_price' => 'permit_empty|decimal',
        'cost_price'       => 'permit_empty|decimal',
        'stock_quantity'   => 'permit_empty|integer',
        'manage_stock'     => 'permit_empty|in_list[0,1]',
        'stock_status'     => 'permit_empty|in_list[in_stock,out_of_stock,on_backorder]',
        'weight'           => 'permit_empty|decimal',
        'is_active'        => 'permit_empty|in_list[0,1]',
        'is_featured'      => 'permit_empty|in_list[0,1]',
    ];

    protected $validationMessages = [];
    protected $skipValidation     = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['processGallery'];
    protected $beforeUpdate   = ['processGallery'];

    /**
     * Process gallery JSON before insert/update
     */
    protected function processGallery(array $data)
    {
        if (isset($data['data']['gallery']) && is_array($data['data']['gallery'])) {
            $data['data']['gallery'] = json_encode($data['data']['gallery']);
        }
        return $data;
    }

    /**
     * Get active products only
     */
    public function getActiveProducts()
    {
        return $this->where('is_active', 1)
                    ->orderBy('sort_order', 'ASC')
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }

    /**
     * Find product by slug
     */
    public function findBySlug(string $slug)
    {
        $product = $this->where('slug', $slug)
                        ->where('is_active', 1)
                        ->first();
        
        if ($product && $product['gallery']) {
            $product['gallery'] = json_decode($product['gallery'], true) ?? [];
        }
        
        return $product;
    }

    /**
     * Find product by SKU
     */
    public function findBySku(string $sku)
    {
        return $this->where('sku', $sku)->first();
    }

    /**
     * Get featured products
     */
    public function getFeaturedProducts(int $limit = 10)
    {
        return $this->where('is_featured', 1)
                    ->where('is_active', 1)
                    ->where('stock_status', 'in_stock')
                    ->orderBy('sort_order', 'ASC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Get products by category
     */
    public function getByCategory(int $categoryId, int $limit = null)
    {
        $builder = $this->where('category_id', $categoryId)
                        ->where('is_active', 1)
                        ->orderBy('sort_order', 'ASC')
                        ->orderBy('name', 'ASC');
        
        if ($limit) {
            $builder->limit($limit);
        }
        
        return $builder->findAll();
    }

    /**
     * Get products with category relationship
     */
    public function getWithCategory(int $productId)
    {
        $product = $this->find($productId);
        if ($product && $product['category_id']) {
            $categoryModel = new CategoryModel();
            $product['category'] = $categoryModel->find($product['category_id']);
        }
        if ($product && $product['gallery']) {
            $product['gallery'] = json_decode($product['gallery'], true) ?? [];
        }
        return $product;
    }

    /**
     * Search products
     */
    public function searchProducts(string $query, int $limit = 20)
    {
        return $this->groupStart()
                    ->like('name', $query)
                    ->orLike('description', $query)
                    ->orLike('sku', $query)
                    ->groupEnd()
                    ->where('is_active', 1)
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Update stock quantity
     */
    public function updateStock(int $productId, int $quantity)
    {
        $product = $this->find($productId);
        if (!$product) {
            return false;
        }

        $newQuantity = $product['stock_quantity'] + $quantity;
        $stockStatus = 'in_stock';
        
        if ($newQuantity <= 0) {
            $stockStatus = 'out_of_stock';
            $newQuantity = 0;
        }

        return $this->update($productId, [
            'stock_quantity' => $newQuantity,
            'stock_status' => $stockStatus,
        ]);
    }

    /**
     * Increment view count
     */
    public function incrementViews(int $productId)
    {
        $product = $this->find($productId);
        if ($product) {
            return $this->update($productId, [
                'views_count' => ($product['views_count'] ?? 0) + 1,
            ]);
        }
        return false;
    }

    /**
     * Check if product is in stock
     */
    public function isInStock(int $productId): bool
    {
        $product = $this->find($productId);
        if (!$product) {
            return false;
        }

        if (!$product['manage_stock']) {
            return $product['stock_status'] === 'in_stock';
        }

        return $product['stock_quantity'] > 0 && $product['stock_status'] === 'in_stock';
    }
}
