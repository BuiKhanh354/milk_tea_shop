<?php
// pages/auth/logout.php — Đăng xuất khách hàng và quản trị viên, chuyển hướng về trang chủ
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

unset(
    $_SESSION['customer_id'],
    $_SESSION['customer_name'],
    $_SESSION['customer_email'],
    $_SESSION['customer_phone'],
    $_SESSION['user_id'],
    $_SESSION['username'],
    $_SESSION['full_name'],
    $_SESSION['role']
);

set_flash('success', 'Bạn đã đăng xuất thành công.');
redirect(url('home'));
