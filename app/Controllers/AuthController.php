<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Show registration form
     */
    public function register()
    {
        // Redirect if already logged in
        if ($this->isLoggedIn()) {
            return redirect()->to('/');
        }

        $data = [
            'title' => 'Register',
            'validation' => \Config\Services::validation(),
        ];

        return view('auth/register', $data);
    }

    /**
     * Process registration
     */
    public function processRegister()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'first_name' => 'required|min_length[2]|max_length[100]',
            'last_name'  => 'required|min_length[2]|max_length[100]',
            'email'      => 'required|valid_email|max_length[255]|is_unique[users.email]',
            'password'   => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $validation);
        }

        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name'  => $this->request->getPost('last_name'),
            'email'      => $this->request->getPost('email'),
            'password'   => $this->request->getPost('password'),
            'role'       => 'customer',
            'status'     => 'active',
        ];

        $userId = $this->userModel->insert($data);

        if ($userId) {
            // Auto login after registration
            $this->session->set([
                'user_id' => $userId,
                'user_email' => $data['email'],
                'user_name' => $data['first_name'] . ' ' . $data['last_name'],
                'user_role' => 'customer',
            ]);

            return redirect()->to('/')->with('success', 'Registration successful! Welcome to DevCart.');
        }

        return redirect()->back()->withInput()->with('error', 'Registration failed. Please try again.');
    }

    /**
     * Show login form
     */
    public function login()
    {
        // Redirect if already logged in
        if ($this->isLoggedIn()) {
            return redirect()->to('/');
        }

        $data = [
            'title' => 'Login',
            'validation' => \Config\Services::validation(),
        ];

        return view('auth/login', $data);
    }

    /**
     * Process login
     */
    public function processLogin()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $validation);
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $this->userModel->findByEmail($email);

        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'Invalid email or password.');
        }

        // Check if account is active
        if ($user['status'] !== 'active') {
            return redirect()->back()->withInput()->with('error', 'Your account is inactive. Please contact support.');
        }

        // Verify password
        if (!$this->userModel->verifyPassword($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Invalid email or password.');
        }

        // Set session
        $this->session->set([
            'user_id' => $user['id'],
            'user_email' => $user['email'],
            'user_name' => $user['first_name'] . ' ' . $user['last_name'],
            'user_role' => $user['role'],
        ]);

        // Transfer cart from session to user if exists
        if ($this->session->has('session_id')) {
            $cartModel = new \App\Models\CartItemModel();
            $cartModel->transferCartToUser($this->session->get('session_id'), $user['id']);
        }

        // Redirect based on role
        $redirectUrl = $this->request->getGet('redirect') ?? '/';
        if ($user['role'] === 'admin') {
            $redirectUrl = '/admin';
        }

        return redirect()->to($redirectUrl)->with('success', 'Welcome back, ' . $user['first_name'] . '!');
    }

    /**
     * Logout
     */
    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/')->with('success', 'You have been logged out successfully.');
    }

    /**
     * Show forgot password form
     */
    public function forgotPassword()
    {
        if ($this->isLoggedIn()) {
            return redirect()->to('/');
        }

        $data = [
            'title' => 'Forgot Password',
        ];

        return view('auth/forgot_password', $data);
    }

    /**
     * Process forgot password
     */
    public function processForgotPassword()
    {
        $email = $this->request->getPost('email');
        
        if (empty($email)) {
            return redirect()->back()->withInput()->with('error', 'Please enter your email address.');
        }

        $user = $this->userModel->findByEmail($email);

        if ($user) {
            // In a real application, you would send an email with reset link
            // For now, we'll just show a success message
            return redirect()->to('/login')->with('success', 'If that email exists, a password reset link has been sent.');
        }

        // Don't reveal if email exists or not (security best practice)
        return redirect()->to('/login')->with('success', 'If that email exists, a password reset link has been sent.');
    }
}
