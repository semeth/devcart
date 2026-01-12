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
    $routes->get('categories', 'AdminController::categories');
    $routes->get('orders', 'AdminController::orders');
});

// Legacy routes (can be removed if not needed)
$routes->get('news', 'News::index');
$routes->get('news/new', 'News::new');
$routes->post('news', 'News::create');
$routes->get('news/(:segment)', 'News::show/$1');

$routes->get('pages', 'Pages::index');
$routes->get('(:segment)', 'Pages::view/$1');