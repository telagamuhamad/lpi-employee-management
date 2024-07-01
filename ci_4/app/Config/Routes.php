<?php

use App\Controllers\UserController;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('dashboard', 'Dashboard::index');
$routes->get('/logout', 'AuthController::logout');

// User management
$routes->get('/users', 'UserController::index');
$routes->get('/users/create', 'UserController::create');
$routes->post('/users/store', 'UserController::store');
$routes->get('/users/(:num)', 'UserController::show/$1');
$routes->put('/users/(:num)', 'UserController::update/$1');
$routes->delete('/users/delete/(:num)', 'UserController::delete/$1');

// Employee management
$routes->get('/employees', 'EmployeeController::index');
$routes->get('/employees/create', 'EmployeeController::create');
$routes->post('/employees/store', 'EmployeeController::store');
$routes->get('/employees/(:num)', 'EmployeeController::show/$1');
$routes->put('/employees/(:num)', 'EmployeeController::update/$1');
$routes->delete('/employees/delete/(:num)', 'EmployeeController::delete/$1');

