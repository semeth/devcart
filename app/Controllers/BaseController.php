<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    protected $session;
    protected $userModel;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Load here all helpers you want to be available in your controllers that extend BaseController.
        // Caution: Do not put the this below the parent::initController() call below.
        $this->helpers = ['form', 'url', 'html'];

        // Caution: Do not edit this line.
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        $this->session = service('session');
        $this->userModel = new \App\Models\UserModel();
    }

    /**
     * Check if user is logged in
     */
    protected function isLoggedIn(): bool
    {
        return $this->session->has('user_id');
    }

    /**
     * Get current user ID
     */
    protected function getUserId(): ?int
    {
        return $this->session->get('user_id');
    }

    /**
     * Get current user data
     */
    protected function getUser(): ?array
    {
        if (!$this->isLoggedIn()) {
            return null;
        }
        
        $userId = $this->getUserId();
        return $this->userModel->find($userId);
    }

    /**
     * Check if user is admin
     */
    protected function isAdmin(): bool
    {
        $user = $this->getUser();
        return $user && $user['role'] === 'admin';
    }

    /**
     * Require login - redirect if not logged in
     */
    protected function requireLogin()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('/login')->with('error', 'Please login to continue');
        }
    }

    /**
     * Require admin - redirect if not admin
     */
    protected function requireAdmin()
    {
        $this->requireLogin();
        if (!$this->isAdmin()) {
            return redirect()->to('/')->with('error', 'Access denied. Admin only.');
        }
    }
}
