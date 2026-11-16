<?php
// ROUTER PUBLIC: chuẩn kiến trúc minishop-basic
require_once __DIR__ . '/config/init.php';

$routes = array(
    'home'           => 'home/index.php',
    'products'       => 'products/index.php',
    'product-detail' => 'product-detail/index.php',
    'product_detail' => 'product-detail/index.php',
    'cart'           => 'cart/index.php',
    'checkout'       => 'checkout/index.php',
    'about'          => 'about/index.php',
    'contact'        => 'contact/index.php',
    'stores'         => 'stores/index.php',
    'yeuthich'       => 'yeuthich/index.php',
    'favorites'      => 'yeuthich/index.php',
    'login'          => 'auth/login.php',
    'register'       => 'auth/register.php',
    'logout'         => 'auth/logout.php',
    'profile'        => 'profile/index.php',
    'password'       => 'profile/index.php',
    'orders'         => 'orders/index.php',
    'order-detail'   => 'orders/detail.php',
);

$rawParam = $_GET['page'] ?? ($_GET['route'] ?? 'home');
$aliases = array(
    'product_detail' => 'product-detail',
    'favorites'      => 'yeuthich',
    'password'       => 'profile',
);
$page = $aliases[$rawParam] ?? $rawParam;

if (!is_string($page) || !isset($routes[$page])) {
    http_response_code(404);
    $file = __DIR__ . '/pages/404/index.php';
} else {
    $file = __DIR__ . '/pages/' . $routes[$page];
}

if (!is_file($file)) {
    http_response_code(404);
    $file = __DIR__ . '/pages/404/index.php';
}

$action = dirname($file) . '/action.php';
if (is_file($action)) {
    require $action;
}

// Các trang độc lập không dùng chung header/footer của website
$standalonePages = array('login', 'register', 'logout');

if (in_array($page, $standalonePages)) {
    require $file;
} else {
    require __DIR__ . '/layouts/public/header.php';
    require $file;
    require __DIR__ . '/layouts/public/footer.php';
}
