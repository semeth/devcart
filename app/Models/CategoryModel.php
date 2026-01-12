<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table            = 'categories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'slug',
        'description',
        'parent_id',
        'image',
        'sort_order',
        'is_active',
        'meta_title',
        'meta_description',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'name'        => 'required|min_length[2]|max_length[100]',
        'slug'        => 'required|alpha_dash|max_length[100]|is_unique[categories.slug,id,{id}]',
        'description' => 'permit_empty',
        'parent_id'   => 'permit_empty|integer',
        'image'       => 'permit_empty|max_length[255]',
        'sort_order'  => 'permit_empty|integer',
        'is_active'   => 'permit_empty|in_list[0,1]',
    ];

    protected $validationMessages = [];
    protected $skipValidation     = false;
    protected $cleanValidationRules = true;

    /**
     * Get active categories only
     */
    public function getActiveCategories()
    {
        return $this->where('is_active', 1)
                    ->orderBy('sort_order', 'ASC')
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }

    /**
     * Find category by slug
     */
    public function findBySlug(string $slug)
    {
        return $this->where('slug', $slug)
                    ->where('is_active', 1)
                    ->first();
    }

    /**
     * Get top-level categories (no parent)
     */
    public function getTopLevelCategories()
    {
        return $this->where('parent_id', null)
                    ->where('is_active', 1)
                    ->orderBy('sort_order', 'ASC')
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }

    /**
     * Get child categories
     */
    public function getChildCategories(int $parentId)
    {
        return $this->where('parent_id', $parentId)
                    ->where('is_active', 1)
                    ->orderBy('sort_order', 'ASC')
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }

    /**
     * Get category with parent
     */
    public function getWithParent(int $categoryId)
    {
        $category = $this->find($categoryId);
        if ($category && $category['parent_id']) {
            $category['parent'] = $this->find($category['parent_id']);
        }
        return $category;
    }

    /**
     * Get category tree (hierarchical structure)
     */
    public function getCategoryTree()
    {
        $topLevel = $this->getTopLevelCategories();
        foreach ($topLevel as &$category) {
            $category['children'] = $this->getChildCategories($category['id']);
        }
        return $topLevel;
    }

    /**
     * Check if category has children
     */
    public function hasChildren(int $categoryId): bool
    {
        return $this->where('parent_id', $categoryId)->countAllResults() > 0;
    }

    /**
     * Get all descendant category IDs (including nested subcategories)
     * Returns an array of category IDs including the parent and all children recursively
     */
    public function getAllDescendantIds(int $categoryId): array
    {
        $categoryIds = [$categoryId]; // Include the parent category itself
        
        // Get direct children
        $children = $this->where('parent_id', $categoryId)
                        ->where('is_active', 1)
                        ->findAll();
        
        // Recursively get children of children
        foreach ($children as $child) {
            $categoryIds[] = $child['id'];
            $descendants = $this->getAllDescendantIds($child['id']);
            $categoryIds = array_merge($categoryIds, $descendants);
        }
        
        return array_unique($categoryIds);
    }
}
