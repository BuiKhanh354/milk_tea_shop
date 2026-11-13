<?php
// pages/auth/action.php — Xử lý đăng nhập / đăng ký / đăng xuất
$action = get_param('page');

if ($action === 'logout') {
    unset($_SESSION['customer_id'], $_SESSION['customer_name'], $_SESSION['customer_email'], $_SESSION['customer_phone']);
    redirect(url('home'));
}

$title = ($action === 'register') ? 'Đăng ký tài khoản' : 'Đăng nhập';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'login') {
        $loginInput = trim($_POST['login'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($loginInput === '' || $password === '') {
            $error = 'Vui lòng điền đầy đủ thông tin đăng nhập.';
        } else {
            // Kiểm tra email hoặc phone trong bảng customers
            $customer = db()->fetchOne("SELECT * FROM customers WHERE email = ? OR phone = ?", array($loginInput, $loginInput));
            if ($customer && password_verify($password, $customer['password'])) {
                $_SESSION['customer_id'] = $customer['id'];
                $_SESSION['customer_name'] = $customer['name'];
                $_SESSION['customer_email'] = $customer['email'];
                $_SESSION['customer_phone'] = $customer['phone'];
                redirect(url('home'));
            } else {
                $error = 'Tài khoản hoặc mật khẩu không chính xác.';
            }
        }
    }

    if ($action === 'register') {
        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if ($name === '' || $phone === '' || $password === '') {
            $error = 'Vui lòng điền các thông tin bắt buộc.';
        } elseif ($password !== $confirmPassword) {
            $error = 'Mật khẩu xác nhận không khớp.';
        } else {
            $existing = db()->fetchOne("SELECT id FROM customers WHERE phone = ? OR (email != '' AND email = ?)", array($phone, $email));
            if ($existing) {
                $error = 'Số điện thoại hoặc Email này đã được đăng ký.';
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                db()->execute("INSERT INTO customers (name, phone, email, password, created_at) VALUES (?, ?, ?, ?, NOW())",
                    array($name, $phone, $email, $hashedPassword)
                );
                $newId = db()->insertId();
                $_SESSION['customer_id'] = $newId;
                $_SESSION['customer_name'] = $name;
                $_SESSION['customer_email'] = $email;
                $_SESSION['customer_phone'] = $phone;
                redirect(url('home'));
            }
        }
    }
}

