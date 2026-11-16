<?php
// admin/pages/staff/action.php — Quản lý nhân viên
$action = get_param('action', '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'store') {
        $username = post_param('username');
        $password = post_param('password');
        $fullName = post_param('full_name');
        $email = post_param('email');
        $phone = post_param('phone');

        if (!empty($username) && !empty($password) && !empty($fullName)) {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            try {
                db()->execute("INSERT INTO users (username, password, full_name, email, phone, role, status) VALUES (?, ?, ?, ?, ?, 'staff', 1)", [$username, $hashed, $fullName, $email, $phone]);
                set_flash('success', "Thêm nhân viên mới thành công!");
            } catch (Exception $e) {
                set_flash('error', "Tên đăng nhập hoặc email đã tồn tại!");
            }
        }
        redirect(admin_url('staff'));
    }

    if ($action === 'update') {
        $id = (int)post_param('id');
        $username = post_param('username');
        $password = post_param('password');
        $fullName = post_param('full_name');
        $email = post_param('email');
        $phone = post_param('phone');

        if ($id && !empty($username) && !empty($fullName)) {
            try {
                if (!empty($password)) {
                    $hashed = password_hash($password, PASSWORD_DEFAULT);
                    db()->execute("UPDATE users SET username = ?, password = ?, full_name = ?, email = ?, phone = ? WHERE id = ? AND role = 'staff'", [$username, $hashed, $fullName, $email, $phone, $id]);
                } else {
                    db()->execute("UPDATE users SET username = ?, full_name = ?, email = ?, phone = ? WHERE id = ? AND role = 'staff'", [$username, $fullName, $email, $phone, $id]);
                }
                set_flash('success', "Cập nhật nhân viên thành công!");
            } catch (Exception $e) {
                set_flash('error', "Có lỗi xảy ra hoặc username đã tồn tại!");
            }
        }
        redirect(admin_url('staff'));
    }

    if ($action === 'toggle_status') {
        $id = (int)post_param('id');
        if ($id) {
            db()->execute("UPDATE users SET status = CASE WHEN status = 1 THEN 0 ELSE 1 END WHERE id = ? AND role = 'staff'", [$id]);
            set_flash('success', "Đã thay đổi trạng thái tài khoản nhân viên!");
        }
        redirect(admin_url('staff'));
    }
}

$staffs = db()->fetchAll("SELECT * FROM users WHERE role = 'staff' ORDER BY created_at DESC");
$pageTitle = 'Quản lý Nhân viên';

