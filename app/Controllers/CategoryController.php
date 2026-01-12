<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\ProductModel;

class CategoryController extends BaseController
{
    protected $categoryModel;
    protected $productModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
        $this->productModel = new ProductModel();
    }

    /**
     * Show category page with products
     */
    public function show($slug)
    {
        $category = $this->categoryModel->findBySlug($slug);

        if (!$category) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Get products in this category and all subcategories
        $categoryIds = $this->categoryModel->getAllDescendantIds($category['id']);
        $products = $this->productModel->getByCategories($categoryIds);

        // Get child categories
        $childCategories = $this->categoryModel->getChildCategories($category['id']);

        // Get parent category if exists
        $parentCategory = null;
        if ($category['parent_id']) {
            $parentCategory = $this->categoryModel->find($category['parent_id']);
        }

        $data = [
            'title' => $category['name'],
            'category' => $category,
            'products' => $products,
            'childCategories' => $childCategories,
            'parentCategory' => $parentCategory,
            'categories' => $this->categoryModel->getActiveCategories(),
        ];

        return view('categories/show', $data);
    }

    /**
     * List all categories
     */
    public function index()
    {
        $data = [
            'title' => 'Categories',
            'categories' => $this->categoryModel->getCategoryTree(),
        ];

        return view('categories/index', $data);
    }
}
