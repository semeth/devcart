<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\CategoryModel;

class ProductController extends BaseController
{
    protected $productModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->categoryModel = new CategoryModel();
    }

    /**
     * List all products
     */
    public function index()
    {
        $categoryId = $this->request->getGet('category');
        $search = $this->request->getGet('search');
        $page = $this->request->getGet('page') ?? 1;

        $data = [
            'title' => 'Products',
        ];

        if ($search) {
            $data['products'] = $this->productModel->searchProducts($search);
            $data['search'] = $search;
        } elseif ($categoryId) {
            $data['products'] = $this->productModel->getByCategory($categoryId);
            $data['category'] = $this->categoryModel->find($categoryId);
        } else {
            $data['products'] = $this->productModel->getActiveProducts();
        }

        $data['categories'] = $this->categoryModel->getActiveCategories();

        return view('products/index', $data);
    }

    /**
     * Show product details
     */
    public function show($slug)
    {
        $product = $this->productModel->findBySlug($slug);

        if (!$product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Increment view count
        $this->productModel->incrementViews($product['id']);

        // Get related products (same category)
        $relatedProducts = [];
        if ($product['category_id']) {
            $relatedProducts = $this->productModel->getByCategory($product['category_id'], 4);
            // Remove current product from related
            $relatedProducts = array_filter($relatedProducts, function($p) use ($product) {
                return $p['id'] != $product['id'];
            });
        }

        $data = [
            'title' => $product['name'],
            'product' => $product,
            'relatedProducts' => array_slice($relatedProducts, 0, 4),
        ];

        return view('products/show', $data);
    }

    /**
     * Search products
     */
    public function search()
    {
        $query = $this->request->getGet('q');

        if (empty($query)) {
            return redirect()->to('/products');
        }

        $products = $this->productModel->searchProducts($query);

        $data = [
            'title' => 'Search Results',
            'products' => $products,
            'search' => $query,
            'categories' => $this->categoryModel->getActiveCategories(),
        ];

        return view('products/index', $data);
    }
}
