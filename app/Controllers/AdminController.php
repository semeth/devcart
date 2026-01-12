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
     * Show product form (create/edit)
     */
    public function productForm($id = null)
    {
        $this->requireAdmin();

        $product = null;
        if ($id) {
            $product = $this->productModel->find($id);
            if (!$product) {
                return redirect()->to('/admin/products')->with('error', 'Product not found.');
            }
        }

        $categories = $this->categoryModel->getActiveCategories();

        $data = [
            'title' => $id ? 'Edit Product' : 'Add Product',
            'product' => $product,
            'categories' => $categories,
            'validation' => \Config\Services::validation(),
        ];

        return view('admin/products/form', $data);
    }

    /**
     * Save product (create/update)
     */
    public function productSave($id = null)
    {
        $this->requireAdmin();

        $validation = \Config\Services::validation();

        $rules = [
            'name' => 'required|min_length[2]|max_length[255]',
            'slug' => 'required|alpha_dash|max_length[255]' . ($id ? '|is_unique[products.slug,id,' . $id . ']' : '|is_unique[products.slug]'),
            'sku' => 'required|alpha_numeric|max_length[100]' . ($id ? '|is_unique[products.sku,id,' . $id . ']' : '|is_unique[products.sku]'),
            'price' => 'required|decimal',
            'stock_quantity' => 'permit_empty|integer',
            'image' => 'uploaded[image]|max_size[image,2048]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/gif,image/webp]',
        ];

        // Make image validation optional
        if (!$this->request->getFile('image')->isValid()) {
            unset($rules['image']);
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $validation);
        }

        // Handle image upload
        $imagePath = null;
        $file = $this->request->getFile('image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $imagePath = $this->uploadImage($file, 'products');
            if (!$imagePath) {
                return redirect()->back()->withInput()->with('error', 'Failed to upload image.');
            }
        } elseif ($id) {
            // Keep existing image if no new file uploaded
            $existing = $this->productModel->find($id);
            $imagePath = $existing['image'] ?? null;
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'slug' => $this->request->getPost('slug'),
            'sku' => $this->request->getPost('sku'),
            'description' => $this->request->getPost('description'),
            'short_description' => $this->request->getPost('short_description'),
            'category_id' => $this->request->getPost('category_id') ?: null,
            'price' => $this->request->getPost('price'),
            'compare_at_price' => $this->request->getPost('compare_at_price') ?: null,
            'cost_price' => $this->request->getPost('cost_price') ?: null,
            'stock_quantity' => $this->request->getPost('stock_quantity') ?? 0,
            'manage_stock' => $this->request->getPost('manage_stock') ? 1 : 0,
            'stock_status' => $this->request->getPost('stock_status') ?? 'in_stock',
            'weight' => $this->request->getPost('weight') ?: null,
            'dimensions' => $this->request->getPost('dimensions') ?: null,
            'image' => $imagePath,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
            'is_featured' => $this->request->getPost('is_featured') ? 1 : 0,
            'sort_order' => $this->request->getPost('sort_order') ?? 0,
            'meta_title' => $this->request->getPost('meta_title') ?: null,
            'meta_description' => $this->request->getPost('meta_description') ?: null,
        ];

        if ($id) {
            $this->productModel->update($id, $data);
            $message = 'Product updated successfully.';
        } else {
            $this->productModel->insert($data);
            $message = 'Product created successfully.';
        }

        return redirect()->to('/admin/products')->with('success', $message);
    }

    /**
     * Delete product
     */
    public function productDelete($id)
    {
        $this->requireAdmin();

        $product = $this->productModel->find($id);
        if (!$product) {
            return redirect()->to('/admin/products')->with('error', 'Product not found.');
        }

        // Delete associated image if exists
        if (!empty($product['image']) && file_exists(ROOTPATH . 'public/' . $product['image'])) {
            unlink(ROOTPATH . 'public/' . $product['image']);
        }

        $this->productModel->delete($id);
        return redirect()->to('/admin/products')->with('success', 'Product deleted successfully.');
    }

    /**
     * Categories management
     */
    public function categories()
    {
        $this->requireAdmin();

        $categories = $this->categoryModel->findAll();
        
        // Load parent names for each category
        foreach ($categories as &$category) {
            if ($category['parent_id']) {
                $parent = $this->categoryModel->find($category['parent_id']);
                $category['parent_name'] = $parent ? $parent['name'] : null;
            }
        }

        $data = [
            'title' => 'Manage Categories',
            'categories' => $categories,
        ];

        return view('admin/categories/index', $data);
    }

    /**
     * Show category form (create/edit)
     */
    public function categoryForm($id = null)
    {
        $this->requireAdmin();

        $category = null;
        if ($id) {
            $category = $this->categoryModel->find($id);
            if (!$category) {
                return redirect()->to('/admin/categories')->with('error', 'Category not found.');
            }
        }

        // Get all categories for parent selection (exclude current category if editing)
        $allCategories = $this->categoryModel->findAll();
        $parentOptions = [];
        foreach ($allCategories as $cat) {
            if ($id && $cat['id'] == $id) {
                continue; // Don't allow category to be its own parent
            }
            $parentOptions[] = $cat;
        }

        $data = [
            'title' => $id ? 'Edit Category' : 'Add Category',
            'category' => $category,
            'parentOptions' => $parentOptions,
            'validation' => \Config\Services::validation(),
        ];

        return view('admin/categories/form', $data);
    }

    /**
     * Save category (create/update)
     */
    public function categorySave($id = null)
    {
        $this->requireAdmin();

        $validation = \Config\Services::validation();

        $rules = [
            'name' => 'required|min_length[2]|max_length[100]',
            'slug' => 'required|alpha_dash|max_length[100]' . ($id ? '|is_unique[categories.slug,id,' . $id . ']' : '|is_unique[categories.slug]'),
            'parent_id' => 'permit_empty|integer',
            'image' => 'uploaded[image]|max_size[image,2048]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/gif,image/webp]',
        ];

        // Make image validation optional
        if (!$this->request->getFile('image')->isValid()) {
            unset($rules['image']);
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $validation);
        }

        // Prevent category from being its own parent
        $parentId = $this->request->getPost('parent_id') ?: null;
        if ($id && $parentId == $id) {
            return redirect()->back()->withInput()->with('error', 'A category cannot be its own parent.');
        }

        // Handle image upload
        $imagePath = null;
        $file = $this->request->getFile('image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $imagePath = $this->uploadImage($file, 'categories');
            if (!$imagePath) {
                return redirect()->back()->withInput()->with('error', 'Failed to upload image.');
            }
        } elseif ($id) {
            // Keep existing image if no new file uploaded
            $existing = $this->categoryModel->find($id);
            $imagePath = $existing['image'] ?? null;
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'slug' => $this->request->getPost('slug'),
            'description' => $this->request->getPost('description'),
            'parent_id' => $parentId,
            'image' => $imagePath,
            'sort_order' => $this->request->getPost('sort_order') ?? 0,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
            'meta_title' => $this->request->getPost('meta_title') ?: null,
            'meta_description' => $this->request->getPost('meta_description') ?: null,
        ];

        if ($id) {
            $this->categoryModel->update($id, $data);
            $message = 'Category updated successfully.';
        } else {
            $this->categoryModel->insert($data);
            $message = 'Category created successfully.';
        }

        return redirect()->to('/admin/categories')->with('success', $message);
    }

    /**
     * Delete category
     */
    public function categoryDelete($id)
    {
        $this->requireAdmin();

        $category = $this->categoryModel->find($id);
        if (!$category) {
            return redirect()->to('/admin/categories')->with('error', 'Category not found.');
        }

        // Check if category has children
        if ($this->categoryModel->hasChildren($id)) {
            return redirect()->to('/admin/categories')->with('error', 'Cannot delete category with subcategories. Please delete or move subcategories first.');
        }

        // Delete associated image if exists
        if (!empty($category['image']) && file_exists(ROOTPATH . 'public/' . $category['image'])) {
            unlink(ROOTPATH . 'public/' . $category['image']);
        }

        $this->categoryModel->delete($id);
        return redirect()->to('/admin/categories')->with('success', 'Category deleted successfully.');
    }

    /**
     * Upload image file
     * 
     * @param \CodeIgniter\HTTP\Files\UploadedFile $file
     * @param string $folder Folder name (products, categories, etc.)
     * @return string|null Relative path to uploaded file or null on failure
     */
    protected function uploadImage($file, $folder = 'uploads')
    {
        if (!$file || !$file->isValid()) {
            return null;
        }

        $uploadPath = ROOTPATH . 'public/uploads/' . $folder . '/';
        
        // Create directory if it doesn't exist
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Generate unique filename
        $newName = $file->getRandomName();
        $extension = $file->getExtension();
        
        // Move file
        if ($file->move($uploadPath, $newName)) {
            // Return relative path from public directory
            return 'uploads/' . $folder . '/' . $newName;
        }

        return null;
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
