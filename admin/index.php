<?php
// admin/index.php — Front Controller quản trị theo chuẩn minishop
require_once __DIR__ . '/../config/init.php';

$routes = array(
    'dashboard'           => 'dashboard/index.php',
    'staff_dashboard'     => 'dashboard/staff.php',
    'my-shifts'           => 'shifts/my_shifts.php',
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
    'account'             => 'profile/index.php',
    'login'               => 'auth/login.php',
    'logout'              => 'auth/logout.php',
);

$page = $_GET['page'] ?? 'dashboard';

// Nếu người dùng vào trang login của admin, chuyển hướng sang trang đăng nhập chung
if ($page === 'login') {
    $redirectTarget = get_param('redirect', admin_url('dashboard'));
    redirect(url('login', ['redirect' => $redirectTarget]));
}

// Kiểm tra quyền đăng nhập admin/staff
$publicPages = ['logout'];
if (!in_array($page, $publicPages)) {
    if (!isset($_SESSION['user_id'])) {
        redirect(url('login', ['redirect' => admin_url($page)]));
    }
}

$userRole = $_SESSION['role'] ?? 'guest';
if (!in_array($userRole, ['admin', 'staff']) && !in_array($page, $publicPages)) {
    redirect(url('login'));
}

// Phân quyền cho nhân viên (Staff: chỉ có quyền xuất, không có quyền nhập kho)
$staffAllowedPages = [
    'dashboard', 'staff_dashboard', 'my-shifts',
    'orders', 'order-detail', 'order-create',
    'tables', 'shift_sessions',
    'inventory', 'inventory_export',
    'profile', 'account', 'logout'
];

if ($userRole === 'staff' && !in_array($page, $staffAllowedPages)) {
    set_flash('error', 'Bạn không có quyền truy cập vào chức năng quản trị này!');
    redirect(admin_url('dashboard'));
}

// Điều hướng view cho nhân viên
if ($page === 'dashboard' && $userRole === 'staff') {
    $file = __DIR__ . '/pages/dashboard/staff.php';
} elseif (!is_string($page) || !isset($routes[$page])) {
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
