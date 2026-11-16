<?php
// Tự động chuyển hướng từ public/index.php về hệ thống mới ở thư mục gốc
require_once __DIR__ . '/../config/init.php';

$routeMap = array(
    'home'           => 'home',
    'products'       => 'products',
    'product_detail' => 'product-detail',
    'cart'           => 'cart',
    'checkout'       => 'checkout',
    'favorites'      => 'yeuthich',
    'about'          => 'about',
    'contact'        => 'contact',
    'stores'         => 'stores',
    'login'          => 'login',
    'register'       => 'register',
    'logout'         => 'logout',
    'profile'        => 'profile',
    'orders'         => 'orders',
);

$oldRoute = $_GET['route'] ?? 'home';
$newPage = $routeMap[$oldRoute] ?? 'home';

$queryParams = $_GET;
unset($queryParams['route']);
$queryParams['page'] = $newPage;

redirect(url($newPage, $queryParams));
