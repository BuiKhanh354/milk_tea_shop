<?php
// Admin-only front controller.
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
    'inventory'              => 'inventory/index.php',
    'inventory_import'       => 'inventory/import.php',
    'inventory_export'       => 'inventory/export.php',
    'inventory_alerts'       => 'inventory/alerts.php',
    'inventory_transactions' => 'inventory/transactions.php',
    'inventory_report'       => 'inventory/report.php',
    'inventory_audits'       => 'inventory_audits/index.php',
    'product_ingredients' => 'product_ingredients/index.php',
    'staff'               => 'staff/index.php',
    'shifts'              => 'shifts/index.php',
    'my-shifts'           => 'shifts/my_shifts.php',
    'payments'            => 'payments/index.php',
    'reports'             => 'reports/index.php',
    'reviews'             => 'reviews/index.php',
    'settings'            => 'settings/index.php',
    'profile'             => 'profile/index.php',
    'account'             => 'profile/index.php',
    'login'               => 'auth/login.php',
    'logout'              => 'auth/logout.php',
);

$page = $_GET['page'] ?? 'dashboard';
$page = is_string($page) ? $page : '404';
$userId = (int)($_SESSION['user_id'] ?? 0);
$userRole = $_SESSION['role'] ?? '';

// A staff account must use its own router and shell.
if ($userId > 0 && $userRole === 'staff') {
    $staffPages = array(
        'dashboard', 'my-shifts', 'orders', 'order-detail', 'order-create',
        'tables', 'shift_sessions', 'inventory', 'inventory_export', 'profile',
        'account', 'logout'
    );
    if (in_array($page, $staffPages, true)) {
        $params = $_GET;
        unset($params['page']);
        redirect(staff_url($page, $params));
    }

    set_flash('error', 'Tài khoản nhân viên không có quyền truy cập khu vực quản trị viên.');
    redirect(staff_url('dashboard'));
}

if ($page === 'login') {
    if ($userId > 0 && $userRole === 'admin') {
        redirect(admin_url('dashboard'));
    }
    redirect(url('login', array('redirect' => admin_url('dashboard'))));
}

if ($page !== 'logout' && $userId === 0) {
    $params = $_GET;
    unset($params['page']);
    redirect(url('login', array('redirect' => admin_url($page, $params))));
}

if ($page !== 'logout' && $userRole !== 'admin') {
    redirect(url('login'));
}

if (!isset($routes[$page])) {
    http_response_code(404);
    $file = __DIR__ . '/pages/404/index.php';
} else {
    $file = __DIR__ . '/pages/' . $routes[$page];
}

if (!is_file($file)) {
    http_response_code(404);
    $file = __DIR__ . '/pages/404/index.php';
}

$actionFile = dirname($file) . '/action.php';
if (is_file($actionFile)) {
    require $actionFile;
}

if (in_array($page, array('login', 'logout'), true)) {
    require $file;
} else {
    require __DIR__ . '/layouts/header.php';
    require $file;
    require __DIR__ . '/layouts/footer.php';
}
