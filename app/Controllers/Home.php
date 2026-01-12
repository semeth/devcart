<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\CategoryModel;

class Home extends BaseController
{
    protected $productModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Welcome to DevCart',
            'featuredProducts' => $this->productModel->getFeaturedProducts(8),
            'categories' => $this->categoryModel->getTopLevelCategories(),
        ];

        return view('home', $data);
    }
}
