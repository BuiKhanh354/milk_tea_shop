<?php
// admin/pages/auth/logout.php — Đăng xuất quản trị viên, chuyển hướng về trang đăng nhập admin
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

unset(
    $_SESSION['user_id'],
    $_SESSION['username'],
    $_SESSION['full_name'],
    $_SESSION['role']
);

set_flash('success', 'Bạn đã đăng xuất quản trị thành công.');
redirect(admin_url('login'));
