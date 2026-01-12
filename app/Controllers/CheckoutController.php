<?php

namespace App\Controllers;

use App\Models\CartItemModel;
use App\Models\OrderModel;
use App\Models\AddressModel;
use App\Models\ProductModel;
use App\Services\ExtensionManager;

class CheckoutController extends BaseController
{
    protected $cartModel;
    protected $orderModel;
    protected $addressModel;
    protected $productModel;
    protected $extensionManager;

    public function __construct()
    {
        $this->cartModel = new CartItemModel();
        $this->orderModel = new OrderModel();
        $this->addressModel = new AddressModel();
        $this->productModel = new ProductModel();
        $this->extensionManager = \Config\Services::extensionManager();
    }

    /**
     * Show checkout page
     */
    public function index()
    {
        $userId = $this->getUserId();
        $sessionId = $this->isLoggedIn() ? '' : ($this->session->get('session_id') ?? '');

        // Get cart items
        $cartItems = $this->cartModel->getCartWithProducts($userId, $sessionId);
        
        if (empty($cartItems)) {
            return redirect()->to('/cart')->with('error', 'Your cart is empty.');
        }

        // Validate stock availability
        foreach ($cartItems as $item) {
            if ($item['product_id'] && !$this->productModel->isInStock($item['product_id'])) {
                return redirect()->to('/cart')->with('error', 'Some products in your cart are out of stock.');
            }
        }

        // Get addresses if logged in
        $addresses = [];
        $billingAddress = null;
        $shippingAddress = null;

        if ($userId) {
            $addresses = $this->addressModel->getByUserId($userId);
            $billingAddress = $this->addressModel->getBillingAddress($userId);
            $shippingAddress = $this->addressModel->getShippingAddress($userId);
        }

        // Calculate subtotal
        $subtotal = $this->cartModel->getCartTotal($userId, $sessionId);
        
        // Prepare cart data for extensions
        $cartData = [
            'items' => $cartItems,
            'subtotal' => $subtotal,
        ];
        
        // Prepare address data (use billing address as default for shipping calculation)
        $addressData = [
            'country' => $billingAddress['country'] ?? 'United States',
            'state' => $billingAddress['state'] ?? '',
            'city' => $billingAddress['city'] ?? '',
            'postal_code' => $billingAddress['postal_code'] ?? '',
        ];
        
        // Get active shipping extensions
        $shippingExtensions = $this->extensionManager->getShippingExtensions(true);
        $shippingOptions = [];
        
        foreach ($shippingExtensions as $extension) {
            if ($extension->isActive() && $extension->isAvailableForAddress($addressData)) {
                $options = $extension->getAvailableOptions($cartData, $addressData);
                foreach ($options as $option) {
                    $shippingOptions[] = [
                        'extension_code' => $extension->getCode(),
                        'code' => $option['code'] ?? $extension->getCode(),
                        'name' => $option['name'] ?? $extension->getName(),
                        'cost' => $option['cost'] ?? 0,
                        'estimated_days' => $option['estimated_days'] ?? null,
                        'description' => $extension->getDescription(),
                    ];
                }
            }
        }
        
        // Get active payment extensions
        $paymentExtensions = $this->extensionManager->getPaymentExtensions(true);
        $paymentOptions = [];
        
        foreach ($paymentExtensions as $extension) {
            if ($extension->isActive()) {
                $paymentOptions[] = [
                    'code' => $extension->getCode(),
                    'name' => $extension->getName(),
                    'description' => $extension->getDescription(),
                ];
            }
        }
        
        // Default totals (will be updated by JavaScript when shipping is selected)
        $totals = $this->orderModel->calculateTotals($subtotal, 0, 0, 0);

        $data = [
            'title' => 'Checkout',
            'cartItems' => $cartItems,
            'addresses' => $addresses,
            'billingAddress' => $billingAddress,
            'shippingAddress' => $shippingAddress,
            'totals' => $totals,
            'subtotal' => $subtotal,
            'shippingOptions' => $shippingOptions,
            'paymentOptions' => $paymentOptions,
            'user' => $this->getUser(),
        ];

        return view('checkout/index', $data);
    }

    /**
     * Process checkout
     */
    public function process()
    {
        $userId = $this->getUserId();
        $sessionId = $this->isLoggedIn() ? '' : ($this->session->get('session_id') ?? '');

        // Get cart items
        $cartItems = $this->cartModel->getCartWithProducts($userId, $sessionId);
        
        if (empty($cartItems)) {
            return redirect()->to('/cart')->with('error', 'Your cart is empty.');
        }

        // Validate required fields
        $validation = \Config\Services::validation();
        $rules = [
            'email' => 'required|valid_email',
            'first_name' => 'required',
            'last_name' => 'required',
            'address_line_1' => 'required',
            'city' => 'required',
            'state' => 'required',
            'postal_code' => 'required',
            'country' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $validation);
        }

        // Prepare cart data for order
        $cartData = [
            'email' => $this->request->getPost('email'),
            'items' => [],
            'subtotal' => 0,
            'tax_amount' => 0,
            'shipping_amount' => 0,
            'discount_amount' => 0,
            'currency' => 'USD',
        ];

        $billingAddressId = null;
        $shippingAddressId = null;

        // Save addresses if logged in
        if ($userId) {
            // Billing address
            $billingData = [
                'user_id' => $userId,
                'type' => 'billing',
                'first_name' => $this->request->getPost('first_name'),
                'last_name' => $this->request->getPost('last_name'),
                'address_line_1' => $this->request->getPost('address_line_1'),
                'address_line_2' => $this->request->getPost('address_line_2'),
                'city' => $this->request->getPost('city'),
                'state' => $this->request->getPost('state'),
                'postal_code' => $this->request->getPost('postal_code'),
                'country' => $this->request->getPost('country'),
                'phone' => $this->request->getPost('phone'),
                'is_default' => $this->request->getPost('save_billing') ? 1 : 0,
            ];
            $billingAddressId = $this->addressModel->insert($billingData);

            // Shipping address (if different)
            if ($this->request->getPost('same_as_billing')) {
                $shippingAddressId = $billingAddressId;
            } else {
                $shippingData = [
                    'user_id' => $userId,
                    'type' => 'shipping',
                    'first_name' => $this->request->getPost('shipping_first_name') ?? $billingData['first_name'],
                    'last_name' => $this->request->getPost('shipping_last_name') ?? $billingData['last_name'],
                    'address_line_1' => $this->request->getPost('shipping_address_line_1') ?? $billingData['address_line_1'],
                    'address_line_2' => $this->request->getPost('shipping_address_line_2') ?? $billingData['address_line_2'],
                    'city' => $this->request->getPost('shipping_city') ?? $billingData['city'],
                    'state' => $this->request->getPost('shipping_state') ?? $billingData['state'],
                    'postal_code' => $this->request->getPost('shipping_postal_code') ?? $billingData['postal_code'],
                    'country' => $this->request->getPost('shipping_country') ?? $billingData['country'],
                    'phone' => $this->request->getPost('shipping_phone') ?? $billingData['phone'],
                    'is_default' => 0,
                ];
                $shippingAddressId = $this->addressModel->insert($shippingData);
            }
        }

        // Prepare order items
        foreach ($cartItems as $item) {
            $product = $this->productModel->find($item['product_id']);
            $cartData['items'][] = [
                'product_id' => $item['product_id'],
                'product_name' => $item['name'] ?? 'Product',
                'product_sku' => $product['sku'] ?? null,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ];
            $cartData['subtotal'] += $item['price'] * $item['quantity'];
        }

        // Calculate totals
        $totals = $this->orderModel->calculateTotals($cartData['subtotal'], 0, 0, 0);
        $cartData = array_merge($cartData, $totals);

        // Create order
        $orderId = $this->orderModel->createFromCart($cartData, $userId, $billingAddressId, $shippingAddressId);

        if ($orderId) {
            // Clear cart
            $this->cartModel->clearCart($userId, $sessionId);

            // Get order details
            $order = $this->orderModel->getOrderDetails($orderId);

            return redirect()->to('/orders/' . $order['order_number'])->with('success', 'Order placed successfully!');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to create order. Please try again.');
    }
}
