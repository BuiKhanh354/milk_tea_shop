<?php
// admin/pages/options/action.php — Sugar & Ice options
$action = get_param('action', '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'store') {
        $type = post_param('type', 'sugar');
        $name = post_param('name');
        $value = (int)post_param('value', 0);
        $isDefault = isset($_POST['is_default']) ? 1 : 0;
        $status = isset($_POST['status']) ? 1 : 0;

        if (!empty($name)) {
            db()->execute("INSERT INTO item_options (type, name, value, is_default, status) VALUES (?, ?, ?, ?, ?)", [$type, $name, $value, $isDefault, $status]);
            set_flash('success', "Đã thêm tùy chọn mới!");
        }
        redirect(admin_url('options'));
    }

    if ($action === 'update') {
        $id = (int)post_param('id');
        $type = post_param('type', 'sugar');
        $name = post_param('name');
        $value = (int)post_param('value', 0);
        $isDefault = isset($_POST['is_default']) ? 1 : 0;
        $status = isset($_POST['status']) ? 1 : 0;

        if ($id && !empty($name)) {
            db()->execute("UPDATE item_options SET name=?, value=?, is_default=?, status=? WHERE id=? AND type=?", [$name, $value, $isDefault, $status, $id, $type]);
            set_flash('success', "Đã cập nhật tùy chọn!");
        }
        redirect(admin_url('options'));
    }
}

if ($action === 'delete') {
    $id = (int)get_param('id');
    if ($id) {
        db()->execute("DELETE FROM item_options WHERE id=?", [$id]);
        set_flash('success', "Đã xóa tùy chọn!");
    }
    redirect(admin_url('options'));
}

$sugarOptions = db()->fetchAll("SELECT * FROM item_options WHERE type = 'sugar' ORDER BY value ASC");
$iceOptions = db()->fetchAll("SELECT * FROM item_options WHERE type = 'ice' ORDER BY value ASC");
$pageTitle = 'Tùy chọn (Sugar & Ice)';

