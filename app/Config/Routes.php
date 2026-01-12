<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Home
$routes->get('/', 'Home::index');

// Authentication Routes
$routes->get('register', 'AuthController::register');
$routes->post('register', 'AuthController::processRegister');
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::processLogin');
$routes->get('logout', 'AuthController::logout');
$routes->get('forgot-password', 'AuthController::forgotPassword');
$routes->post('forgot-password', 'AuthController::processForgotPassword');

// Product Routes
$routes->get('products', 'ProductController::index');
$routes->get('products/search', 'ProductController::search');
$routes->get('products/(:segment)', 'ProductController::show/$1');

// Category Routes
$routes->get('categories', 'CategoryController::index');
$routes->get('categories/(:segment)', 'CategoryController::show/$1');

// Cart Routes
$routes->get('cart', 'CartController::index');
$routes->post('cart/add', 'CartController::add');
$routes->post('cart/update', 'CartController::update');
$routes->get('cart/remove/(:num)', 'CartController::remove/$1');
$routes->get('cart/count', 'CartController::count');

// Checkout Routes
$routes->get('checkout', 'CheckoutController::index');
$routes->post('checkout/process', 'CheckoutController::process');

// Order Routes
$routes->get('orders', 'OrderController::index');
$routes->get('orders/(:segment)', 'OrderController::show/$1');

// Admin Routes
$routes->group('admin', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'AdminController::index');
    $routes->get('products', 'AdminController::products');
    $routes->get('products/new', 'AdminController::productForm');
    $routes->get('products/edit/(:num)', 'AdminController::productForm/$1');
    $routes->post('products/save/(:num)', 'AdminController::productSave/$1');
    $routes->post('products/save', 'AdminController::productSave');
    $routes->get('products/delete/(:num)', 'AdminController::productDelete/$1');
    $routes->get('categories', 'AdminController::categories');
    $routes->get('categories/new', 'AdminController::categoryForm');
    $routes->get('categories/edit/(:num)', 'AdminController::categoryForm/$1');
    $routes->post('categories/save', 'AdminController::categorySave');
    $routes->post('categories/save/(:num)', 'AdminController::categorySave/$1');
    $routes->get('categories/delete/(:num)', 'AdminController::categoryDelete/$1');
    $routes->get('orders', 'AdminController::orders');
    $routes->get('orders/(:segment)', 'AdminController::orderShow/$1');
    $routes->post('orders/(:segment)/update', 'AdminController::orderUpdateStatus/$1');
    $routes->get('extensions', 'AdminController::extensions');
    $routes->get('extensions/(:segment)/(:segment)/configure', 'AdminController::extensionConfigure/$1/$2');
    $routes->post('extensions/(:segment)/(:segment)/configure', 'AdminController::extensionConfigure/$1/$2');
    $routes->get('extensions/(:segment)/(:segment)/toggle', 'AdminController::extensionToggle/$1/$2');
});

// Legacy routes (can be removed if not needed)
$routes->get('news', 'News::index');
$routes->get('news/new', 'News::new');
$routes->post('news', 'News::create');
$routes->get('news/(:segment)', 'News::show/$1');

$routes->get('pages', 'Pages::index');
$routes->get('(:segment)', 'Pages::view/$1');