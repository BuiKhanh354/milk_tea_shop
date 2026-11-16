<?php
// Tự động chuyển hướng từ public/admin.php về admin/index.php mới
require_once __DIR__ . '/../config/init.php';

$routeMap = array(
    'dashboard'       => 'dashboard',
    'staff_dashboard' => 'dashboard',
    'products'        => 'products',
    'product_create'  => 'product-create',
    'orders'          => 'orders',
    'login'           => 'login',
    'logout'          => 'logout',
);

$oldRoute = $_GET['route'] ?? 'dashboard';
$newPage = $routeMap[$oldRoute] ?? 'dashboard';

$queryParams = $_GET;
unset($queryParams['route']);
$queryParams['page'] = $newPage;

redirect(admin_url($newPage, $queryParams));
