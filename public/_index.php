<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Base URL configuration for resources
$base_url = 'http://' . $_SERVER['HTTP_HOST'] . str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
define('BASE_URL', rtrim($base_url, '/'));

// Core Controller
require_once __DIR__ . '/../app/core/Controller.php';

// Simple Front Controller Routing
$request_uri = $_SERVER['REQUEST_URI'];
$script_name = $_SERVER['SCRIPT_NAME'];
$base_path = dirname($script_name);
$path = str_replace($base_path, '', $request_uri);
$path = strtok($path, '?');
if ($path == '' || $path == '/index.php') $path = '/';

$controller = new Controller();

if (preg_match('/^\/orders\/([a-zA-Z0-9]+)$/', $path, $matches)) {
    $order_id = $matches[1];
    $controller->view('customer/orders/detail', ['cssFiles' => ['account', 'orders'], 'path' => '/orders', 'order_id' => $order_id], 'main');
    exit;
}

switch ($path) {
    case '/':
    case '/home':
        $controller->view('customer/home/index', ['cssFiles' => ['home'], 'path' => $path], 'main');
        break;
    
    // ==========================================
    // CUSTOMER ACCOUNT ROUTES
    // ==========================================
    case '/profile':
        $controller->view('customer/profile/index', ['cssFiles' => ['account'], 'path' => $path], 'main');
        break;
    case '/profile/edit':
        $controller->view('customer/profile/edit', ['cssFiles' => ['account'], 'path' => $path], 'main');
        break;
    case '/profile/password':
        $controller->view('customer/profile/password', ['cssFiles' => ['account'], 'path' => $path], 'main');
        break;
    case '/orders':
        $controller->view('customer/orders/index', ['cssFiles' => ['account', 'orders'], 'path' => $path], 'main');
        break;
    case '/favorites':
        $controller->view('customer/favorites/index', ['cssFiles' => ['account', 'favorites'], 'path' => $path], 'main');
        break;
    default:
        // Temporary 404
        echo "404 Not Found - Path: " . htmlspecialchars($path);
        break;
}
