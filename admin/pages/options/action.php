<?php
// admin/pages/options/action.php — Sugar & Ice options (OOP)
$action = get_param('action', '');
$optionModel = new ProductOption();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'store') {
        $type = post_param('type', 'sugar');
        $name = post_param('name');
        $value = (int)post_param('value', 0);
        $isDefault = isset($_POST['is_default']) ? 1 : 0;
        $status = isset($_POST['status']) ? 1 : 0;

        if (!empty($name)) {
            $optionModel->store($type, $name, $value, $isDefault, $status);
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
            $optionModel->update($id, $type, $name, $value, $isDefault, $status);
            set_flash('success', "Đã cập nhật tùy chọn!");
        }
        redirect(admin_url('options'));
    }
}

if ($action === 'delete') {
    $id = (int)get_param('id');
    if ($id) {
        $optionModel->deleteById($id);
        set_flash('success', "Đã xóa tùy chọn!");
    }
    redirect(admin_url('options'));
}

$sugarOptions = $optionModel->getByType('sugar');
$iceOptions = $optionModel->getByType('ice');
$pageTitle = 'Tùy chọn (Sugar & Ice)';
