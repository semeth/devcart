<?php

namespace App\Controllers;

use App\Models\CartItemModel;
use App\Models\ProductModel;

class CartController extends BaseController
{
    protected $cartModel;
    protected $productModel;

    public function __construct()
    {
        $this->cartModel = new CartItemModel();
        $this->productModel = new ProductModel();
    }

    /**
     * Show cart
     */
    public function index()
    {
        $userId = $this->getUserId();
        $sessionId = $this->getSessionId();

        $cartItems = $this->cartModel->getCartWithProducts($userId, $sessionId);
        $cartTotal = $this->cartModel->getCartTotal($userId, $sessionId);
        $itemCount = $this->cartModel->getCartItemCount($userId, $sessionId);

        $data = [
            'title' => 'Shopping Cart',
            'cartItems' => $cartItems,
            'cartTotal' => $cartTotal,
            'itemCount' => $itemCount,
        ];

        return view('cart/index', $data);
    }

    /**
     * Add item to cart
     */
    public function add()
    {
        $productId = $this->request->getPost('product_id');
        $quantity = (int)($this->request->getPost('quantity') ?? 1);

        if (!$productId) {
            return redirect()->back()->with('error', 'Product ID is required.');
        }

        // Check if product exists and is in stock
        $product = $this->productModel->find($productId);
        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        if (!$this->productModel->isInStock($productId)) {
            return redirect()->back()->with('error', 'Product is out of stock.');
        }

        // Check if requested quantity is available
        if ($product['manage_stock'] && $quantity > $product['stock_quantity']) {
            return redirect()->back()->with('error', 'Insufficient stock available.');
        }

        $userId = $this->getUserId();
        $sessionId = $this->getSessionId();

        $result = $this->cartModel->addToCart($productId, $quantity, $userId, $sessionId, $product['price']);

        if ($result) {
            return redirect()->to('/cart')->with('success', 'Product added to cart successfully.');
        }

        return redirect()->back()->with('error', 'Failed to add product to cart.');
    }

    /**
     * Update cart item quantity
     */
    public function update()
    {
        $cartItemId = $this->request->getPost('cart_item_id');
        $quantity = (int)($this->request->getPost('quantity') ?? 1);

        if (!$cartItemId) {
            return redirect()->back()->with('error', 'Cart item ID is required.');
        }

        // Verify cart item belongs to user or session
        $cartItem = $this->cartModel->find($cartItemId);
        if (!$cartItem) {
            return redirect()->back()->with('error', 'Cart item not found.');
        }

        $userId = $this->getUserId();
        $sessionId = $this->getSessionId();

        // Check ownership
        if ($userId && $cartItem['user_id'] != $userId) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }
        if (!$userId && $cartItem['session_id'] != $sessionId) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        // Check stock if product still exists
        if ($cartItem['product_id']) {
            $product = $this->productModel->find($cartItem['product_id']);
            if ($product && $product['manage_stock'] && $quantity > $product['stock_quantity']) {
                return redirect()->back()->with('error', 'Insufficient stock available.');
            }
        }

        $result = $this->cartModel->updateQuantity($cartItemId, $quantity);

        if ($result) {
            return redirect()->to('/cart')->with('success', 'Cart updated successfully.');
        }

        return redirect()->back()->with('error', 'Failed to update cart.');
    }

    /**
     * Remove item from cart
     */
    public function remove($id)
    {
        if (!$id) {
            return redirect()->back()->with('error', 'Cart item ID is required.');
        }

        $cartItem = $this->cartModel->find($id);
        if (!$cartItem) {
            return redirect()->back()->with('error', 'Cart item not found.');
        }

        $userId = $this->getUserId();
        $sessionId = $this->getSessionId();

        // Check ownership
        if ($userId && $cartItem['user_id'] != $userId) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }
        if (!$userId && $cartItem['session_id'] != $sessionId) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        $result = $this->cartModel->removeFromCart($id);

        if ($result) {
            return redirect()->to('/cart')->with('success', 'Item removed from cart.');
        }

        return redirect()->back()->with('error', 'Failed to remove item from cart.');
    }

    /**
     * Get session ID (create if doesn't exist)
     */
    protected function getSessionId(): string
    {
        if ($this->isLoggedIn()) {
            return '';
        }

        if (!$this->session->has('session_id')) {
            $sessionId = bin2hex(random_bytes(16));
            $this->session->set('session_id', $sessionId);
        }

        return $this->session->get('session_id');
    }

    /**
     * Get cart count (AJAX endpoint)
     */
    public function count()
    {
        $userId = $this->getUserId();
        $sessionId = $this->getSessionId();

        $count = $this->cartModel->getCartItemCount($userId, $sessionId);

        return $this->response->setJSON(['count' => $count]);
    }
}
