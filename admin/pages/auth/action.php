<?php
// admin/pages/auth/action.php — Điều hướng đăng nhập / đăng xuất quản trị
$page = get_param('page');

if ($page === 'logout') {
    unset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['full_name'], $_SESSION['role']);
    set_flash('success', 'Đã đăng xuất quản trị.');
    redirect(url('login'));
}

if ($page === 'login') {
    $redirectTarget = get_param('redirect', admin_url('dashboard'));
    redirect(url('login', ['redirect' => $redirectTarget]));
}
