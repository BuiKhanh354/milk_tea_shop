<?php
// pages/auth/action.php — Xử lý logic đăng nhập / đăng ký / đăng xuất chung cho Khách hàng, Nhân viên & Quản trị viên
$action = get_param('page', 'login');

if ($action === 'logout') {
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
}

$title = ($action === 'register') ? 'Đăng ký - VAA THÉ' : 'Đăng nhập - VAA THÉ';
$error = '';
$success = '';
$email_val = '';
$redirect_val = post_param('redirect') ?: get_param('redirect', '');

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'login') {
        $loginInput = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $email_val = $loginInput;

        if ($loginInput === '' || $password === '') {
            $error = 'Vui lòng điền đầy đủ tài khoản/email và mật khẩu.';
        } else {
            $staffModel = new Staff();
            $customerModel = new Customer();

            // 1. Kiểm tra tài khoản Quản trị viên / Nhân viên
            $user = $staffModel->findByLogin($loginInput);
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['role'] = $user['role'];

                set_flash('success', 'Đăng nhập thành công! Xin chào ' . $user['full_name'] . ' (' . ucfirst($user['role']) . ').');

                if (!empty($redirect_val)) {
                    redirect($redirect_val);
                }
                if ($user['role'] === 'staff') {
                    redirect(staff_url('dashboard'));
                }
                redirect(admin_url('dashboard'));
            }

            // 2. Kiểm tra tài khoản Khách hàng
            $customer = $customerModel->findByLogin($loginInput);
            if ($customer && password_verify($password, $customer['password'])) {
                $_SESSION['customer_id'] = $customer['id'];
                $_SESSION['customer_name'] = $customer['full_name'];
                $_SESSION['customer_email'] = $customer['email'];
                $_SESSION['customer_phone'] = $customer['phone'];

                set_flash('success', 'Đăng nhập thành công! Chào mừng ' . $customer['full_name'] . ' trở lại.');

                if (!empty($redirect_val)) {
                    redirect($redirect_val);
                }
                redirect(url('home'));
            }

            $error = 'Tài khoản hoặc mật khẩu không chính xác.';
        }
    }

    if ($action === 'register') {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if ($name === '' || $email === '' || $password === '') {
            $error = 'Vui lòng điền các thông tin bắt buộc.';
        } elseif (mb_strlen($name) < 2) {
            $error = 'Họ và tên phải có ít nhất 2 ký tự.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Địa chỉ email không đúng định dạng.';
        } elseif ($password !== $confirmPassword) {
            $error = 'Mật khẩu xác nhận không khớp.';
        } elseif (strlen($password) < 6) {
            $error = 'Mật khẩu phải có ít nhất 6 ký tự.';
        } else {
            $customerModel = new Customer();
            if ($customerModel->findByEmail($email)) {
                $error = 'Địa chỉ Email này đã được đăng ký tài khoản.';
            } else {
                $customerModel->register($name, $email, $password);
                $success = 'Đăng ký tài khoản thành công! Đang chuyển hướng sang đăng nhập...';
            }
        }
    }
}
