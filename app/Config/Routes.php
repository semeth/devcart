<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('news', 'News::index');
$routes->get('news/new', 'News::new');
$routes->post('news', 'News::create');
$routes->get('news/(:segment)', 'News::show/$1');

$routes->get('pages', 'Pages::index');
$routes->get('(:segment)', 'Pages::view/$1');