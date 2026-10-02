<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'AuthController::login');
$routes->match(['get', 'post'], 'login', 'AuthController::login', ['filter' => 'throttle:10,1']);
$routes->get('logout', 'AuthController::logout', ['filter' => 'auth']);

$routes->group('staff', ['filter' => 'role:staff'], static function ($routes): void {
    $routes->get('dashboard', 'StaffController::dashboard');
    $routes->get('history', 'StaffController::history');
});

$routes->group('api', ['filter' => 'auth'], static function ($routes): void {
    $routes->get('qr/current', 'AttendanceController::currentToken', ['filter' => 'role:admin']);
    $routes->post('attendance/scan', 'AttendanceController::scan', ['filter' => 'throttle:10,1']);
    $routes->get('dashboard/stats', 'AdminController::stats', ['filter' => 'role:admin']);
});

$routes->group('admin', ['filter' => 'role:admin'], static function ($routes): void {
    $routes->get('dashboard', 'AdminController::dashboard');
    $routes->get('qr', 'AdminController::qr');
    $routes->get('reports', 'ReportController::index');
    $routes->get('reports/excel', 'ReportController::excel');
    $routes->get('reports/pdf', 'ReportController::pdf');
    $routes->get('(:segment)', 'CrudController::index/$1');
    $routes->post('(:segment)/save', 'CrudController::save/$1');
    $routes->post('(:segment)/delete/(:num)', 'CrudController::delete/$1/$2');
});
