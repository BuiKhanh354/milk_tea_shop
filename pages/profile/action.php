<?php
// pages/profile/action.php — Quản lý thông tin cá nhân khách hàng
if (!isset($_SESSION['customer_id'])) {
    redirect(url('login'));
}

$customerId = (int)$_SESSION['customer_id'];
$customer = db()->fetchOne("SELECT * FROM customers WHERE id = ?", array($customerId));
$title = 'Thông tin cá nhân';
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');

    db()->execute("UPDATE customers SET name = ?, phone = ?, email = ?, address = ? WHERE id = ?",
        array($name, $phone, $email, $address, $customerId)
    );

    $_SESSION['customer_name'] = $name;
    $_SESSION['customer_email'] = $email;
    $_SESSION['customer_phone'] = $phone;

    $customer = db()->fetchOne("SELECT * FROM customers WHERE id = ?", array($customerId));
    $msg = 'Cập nhật thông tin thành công!';
}

