<?php
// admin/index.php — Front Controller quản trị theo chuẩn minishop
require_once __DIR__ . '/../config/init.php';

$routes = array(
    'dashboard'           => 'dashboard/index.php',
    'orders'              => 'orders/index.php',
    'order-detail'        => 'orders/detail.php',
    'order-create'        => 'orders/create.php',
    'tables'              => 'tables/index.php',
    'shift_sessions'      => 'shift_sessions/index.php',
    'customers'           => 'customers/index.php',
    'products'            => 'products/index.php',
    'product-create'      => 'products/create.php',
    'product-edit'        => 'products/edit.php',
    'categories'          => 'categories/index.php',
    'options'             => 'options/index.php',
    'toppings'            => 'toppings/index.php',
    'inventory'           => 'inventory/index.php',
    'inventory_import'    => 'inventory/import.php',
    'inventory_export'    => 'inventory/export.php',
    'inventory_alerts'    => 'inventory/alerts.php',
    'product_ingredients' => 'product_ingredients/index.php',
    'staff'               => 'staff/index.php',
    'shifts'              => 'shifts/index.php',
    'payments'            => 'payments/index.php',
    'reports'             => 'reports/index.php',
    'settings'            => 'settings/index.php',
    'profile'             => 'profile/index.php',
    'login'               => 'auth/login.php',
    'logout'              => 'auth/logout.php',
);

$page = $_GET['page'] ?? 'dashboard';

// Kiểm tra quyền đăng nhập admin
$publicPages = ['login', 'logout'];
if (!in_array($page, $publicPages)) {
    if (!isset($_SESSION['user_id'])) {
        redirect(admin_url('login'));
    }
}

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

if (in_array($page, ['login', 'logout'])) {
    // Đăng nhập / đăng xuất render riêng không kèm header/sidebar admin
    require $file;
} else {
    require __DIR__ . '/layouts/header.php';
    require $file;
    require __DIR__ . '/layouts/footer.php';
}

