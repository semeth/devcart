<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\CategoryModel;
use App\Models\OrderModel;
use App\Models\UserModel;
use App\Models\PaymentModel;

class AdminController extends BaseController
{
    protected $productModel;
    protected $categoryModel;
    protected $orderModel;
    protected $userModel;
    protected $paymentModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->categoryModel = new CategoryModel();
        $this->orderModel = new OrderModel();
        $this->userModel = new UserModel();
        $this->paymentModel = new PaymentModel();
    }

    /**
     * Dashboard
     */
    public function index()
    {
        $this->requireAdmin();

        // Get statistics
        $stats = [
            'total_products' => $this->productModel->countAllResults(),
            'active_products' => $this->productModel->where('is_active', 1)->countAllResults(),
            'total_orders' => $this->orderModel->countAllResults(),
            'pending_orders' => $this->orderModel->where('status', 'pending')->countAllResults(),
            'total_users' => $this->userModel->countAllResults(),
            'total_revenue' => $this->paymentModel->selectSum('amount')
                                                   ->where('status', 'completed')
                                                   ->first()['amount'] ?? 0,
        ];

        // Recent orders
        $recentOrders = $this->orderModel->orderBy('created_at', 'DESC')
                                         ->limit(10)
                                         ->findAll();

        $data = [
            'title' => 'Admin Dashboard',
            'stats' => $stats,
            'recentOrders' => $recentOrders,
        ];

        return view('admin/dashboard', $data);
    }

    /**
     * Products management
     */
    public function products()
    {
        $this->requireAdmin();

        $products = $this->productModel->findAll();

        $data = [
            'title' => 'Manage Products',
            'products' => $products,
        ];

        return view('admin/products/index', $data);
    }

    /**
     * Categories management
     */
    public function categories()
    {
        $this->requireAdmin();

        $categories = $this->categoryModel->findAll();

        $data = [
            'title' => 'Manage Categories',
            'categories' => $categories,
        ];

        return view('admin/categories/index', $data);
    }

    /**
     * Orders management
     */
    public function orders()
    {
        $this->requireAdmin();

        $orders = $this->orderModel->orderBy('created_at', 'DESC')->findAll();

        $data = [
            'title' => 'Manage Orders',
            'orders' => $orders,
        ];

        return view('admin/orders/index', $data);
    }
}
