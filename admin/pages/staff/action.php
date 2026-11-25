<?php
// admin/pages/staff/action.php — Quản lý nhân viên (OOP)
$action = get_param('action', '');
$staffModel = new Staff();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'store') {
        $username = post_param('username');
        $password = post_param('password');
        $fullName = post_param('full_name');
        $email = post_param('email');
        $phone = post_param('phone');

        if (!empty($username) && !empty($password) && !empty($fullName)) {
            try {
                $staffModel->storeStaff($username, $password, $fullName, $email, $phone);
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
                $staffModel->updateStaff($id, $username, $fullName, $email, $phone, $password);
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
            $staffModel->toggleStaffStatus($id);
            set_flash('success', "Đã thay đổi trạng thái tài khoản nhân viên!");
        }
        redirect(admin_url('staff'));
    }
}

$staffs = $staffModel->getAllStaff();
$pageTitle = 'Quản lý Nhân viên';
