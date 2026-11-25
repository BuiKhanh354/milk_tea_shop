<?php
// Staff-only front controller. Operational pages reuse their PHP handlers,
// but render inside the staff-specific layout and router.
require_once __DIR__ . '/../config/init.php';

$routes = array(
    'dashboard'      => '../admin/pages/dashboard/staff.php',
    'my-shifts'       => '../admin/pages/shifts/my_shifts.php',
    'orders'          => '../admin/pages/orders/index.php',
    'order-detail'    => '../admin/pages/orders/detail.php',
    'order-create'    => '../admin/pages/orders/create.php',
    'tables'          => '../admin/pages/tables/index.php',
    'shift_sessions'  => '../admin/pages/shift_sessions/index.php',
    'inventory'       => '../admin/pages/inventory/index.php',
    'inventory_export'=> '../admin/pages/inventory/export.php',
    'profile'         => '../admin/pages/profile/index.php',
    'account'         => '../admin/pages/profile/index.php',
    'logout'          => '../admin/pages/auth/logout.php',
);

$page = $_GET['page'] ?? 'dashboard';
$page = is_string($page) ? $page : '404';
$userId = (int)($_SESSION['user_id'] ?? 0);
$userRole = $_SESSION['role'] ?? '';

if ($userId === 0) {
    $params = $_GET;
    unset($params['page']);
    redirect(url('login', array('redirect' => staff_url($page, $params))));
}

if ($userRole !== 'staff') {
    if ($userRole === 'admin') {
        redirect(admin_url('dashboard'));
    }
    redirect(url('home'));
}

if (!isset($routes[$page])) {
    set_flash('error', 'Trang này không thuộc quyền truy cập của nhân viên.');
    redirect(staff_url('dashboard'));
}

$file = __DIR__ . '/' . $routes[$page];
if (!is_file($file)) {
    http_response_code(404);
    $file = __DIR__ . '/../admin/pages/404/index.php';
}

$actionFile = dirname($file) . '/action.php';
if (is_file($actionFile)) {
    require $actionFile;
}

if ($page === 'logout') {
    require $file;
} else {
    require __DIR__ . '/layouts/header.php';
    require $file;
    require __DIR__ . '/layouts/footer.php';
}
