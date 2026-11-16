<?php
// admin/pages/settings/action.php — Cài đặt hệ thống
$action = get_param('action', '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    set_flash('success', "Đã lưu cài đặt hệ thống thành công!");
    redirect(admin_url('settings'));
}

$pageTitle = 'Cài đặt hệ thống';
