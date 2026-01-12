<?php

namespace App\Controllers;

use App\Models\OrderModel;

class OrderController extends BaseController
{
    protected $orderModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
    }

    /**
     * List user orders
     */
    public function index()
    {
        $this->requireLogin();

        $userId = $this->getUserId();
        $orders = $this->orderModel->getByUserId($userId);

        $data = [
            'title' => 'My Orders',
            'orders' => $orders,
        ];

        return view('orders/index', $data);
    }

    /**
     * Show order details
     */
    public function show($orderNumber)
    {
        $order = $this->orderModel->findByOrderNumber($orderNumber);

        if (!$order) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Check if user owns this order or is admin
        $userId = $this->getUserId();
        if (!$this->isAdmin() && $order['user_id'] != $userId) {
            return redirect()->to('/orders')->with('error', 'Order not found.');
        }

        $orderDetails = $this->orderModel->getOrderDetails($order['id']);

        $data = [
            'title' => 'Order #' . $orderNumber,
            'order' => $orderDetails,
        ];

        return view('orders/show', $data);
    }
}
