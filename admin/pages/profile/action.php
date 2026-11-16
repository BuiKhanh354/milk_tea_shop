<?php
// admin/pages/profile/action.php — Xử lý cập nhật thông tin tài khoản admin/staff
$title = 'Hồ sơ cá nhân';
$userId = $_SESSION['user_id'] ?? 0;

$action = get_param('action', 'index');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'update') {
    $username = post_param('username');
    $fullName = post_param('full_name');
    $phone = post_param('phone');
    $email = post_param('email');
    $password = post_param('password');

    if (!empty($username) && !empty($fullName)) {
        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            db()->execute(
                "UPDATE users SET username = ?, password = ?, full_name = ?, phone = ?, email = ? WHERE id = ?",
                [$username, $hashedPassword, $fullName, $phone, $email, $userId]
            );
        } else {
            db()->execute(
                "UPDATE users SET username = ?, full_name = ?, phone = ?, email = ? WHERE id = ?",
                [$username, $fullName, $phone, $email, $userId]
            );
        }
        $_SESSION['full_name'] = $fullName;
        $_SESSION['username'] = $username;
        set_flash('success', 'Cập nhật thông tin thành công!');
        redirect(admin_url('profile'));
    } else {
        set_flash('error', 'Vui lòng điền đầy đủ tên đăng nhập và họ tên!');
        redirect(admin_url('profile'));
    }
}

$user = db()->fetchOne("SELECT * FROM users WHERE id = ?", [$userId]);
if (!$user) {
    redirect(admin_url('login'));
}

