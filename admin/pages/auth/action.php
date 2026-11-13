<?php
// admin/pages/auth/action.php
$page = get_param('page');

if ($page === 'logout') {
    unset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['full_name'], $_SESSION['role']);
    redirect(admin_url('login'));
}

$title = 'Đăng nhập Quản trị viên';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $user = db()->fetchOne("SELECT * FROM users WHERE username = ? AND status = 1", array($username));
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['role'] = $user['role'];
        redirect(admin_url('dashboard'));
    } else {
        $error = 'Tài khoản hoặc mật khẩu không chính xác.';
    }
}

