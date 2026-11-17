<?php
// pages/profile/action.php — Xử lý nghiệp vụ hồ sơ & đổi mật khẩu tài khoản khách hàng chuẩn gốc VAA THÉ
if (!isset($_SESSION['customer_id'])) {
    redirect(url('login', ['redirect' => url('profile')]));
}

$customerId = (int)$_SESSION['customer_id'];
$customer = db()->fetchOne("SELECT * FROM customers WHERE id = ?", [$customerId]);

if (!$customer) {
    redirect(url('logout'));
}

$pageParam = get_param('page', 'profile');
$tabParam = get_param('tab', '');
$activeTab = ($pageParam === 'password' || $tabParam === 'password') ? 'password' : 'profile';

$success_msg = '';
$error_msg = '';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if ($activeTab === 'password' || isset($_POST['new_password'])) {
        $currentPassword = post_param('current_password');
        $newPassword = post_param('new_password');
        $confirmPassword = post_param('confirm_password');

        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $error_msg = "Vui lòng nhập đầy đủ các trường.";
        } elseif ($newPassword !== $confirmPassword) {
            $error_msg = "Mật khẩu mới không khớp.";
        } elseif (strlen($newPassword) < 6) {
            $error_msg = "Mật khẩu mới phải có ít nhất 6 ký tự.";
        } else {
            if (!empty($customer['password']) && password_verify($currentPassword, $customer['password'])) {
                $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
                db()->execute("UPDATE customers SET password = ? WHERE id = ?", [$newHash, $customerId]);
                $success_msg = "Đổi mật khẩu thành công!";
            } else {
                $error_msg = "Mật khẩu hiện tại không đúng.";
            }
        }
    } else {
        $name = trim(post_param('name'));
        $phone = trim(post_param('phone'));
        $address = trim(post_param('address'));

        if (empty($name)) {
            $error_msg = "Vui lòng nhập họ và tên.";
        } else {
            db()->execute("UPDATE customers SET full_name = ?, phone = ?, address = ? WHERE id = ?", 
                          [$name, $phone, $address, $customerId]);
            $_SESSION['customer_name'] = $name;
            $_SESSION['customer_phone'] = $phone;
            $customer = db()->fetchOne("SELECT * FROM customers WHERE id = ?", [$customerId]);
            $success_msg = "Cập nhật thông tin thành công!";
        }
    }
}

$title = ($activeTab === 'password') ? 'Đổi mật khẩu' : 'Thông tin cá nhân';
