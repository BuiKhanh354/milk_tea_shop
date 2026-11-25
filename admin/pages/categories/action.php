<?php
// admin/pages/categories/action.php — Quản lý danh mục (OOP)
$action = get_param('action', '');
$categoryModel = new Category();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'store') {
        $name = post_param('name');
        $description = post_param('description');
        if (!empty($name)) {
            try {
                $categoryModel->store($name, $description);
                set_flash('success', "Đã thêm danh mục mới thành công!");
            } catch (Exception $e) {
                set_flash('error', "Tên danh mục đã tồn tại hoặc có lỗi xảy ra!");
            }
        }
        redirect(admin_url('categories'));
    }

    if ($action === 'update') {
        $id = (int)post_param('id');
        $name = post_param('name');
        $description = post_param('description');
        if ($id && !empty($name)) {
            try {
                $categoryModel->update($id, $name, $description);
                set_flash('success', "Cập nhật danh mục thành công!");
            } catch (Exception $e) {
                set_flash('error', "Tên danh mục đã tồn tại hoặc có lỗi xảy ra!");
            }
        }
        redirect(admin_url('categories'));
    }

    if ($action === 'delete') {
        $id = (int)post_param('id');
        if ($id) {
            if (!$categoryModel->delete($id)) {
                set_flash('error', "Không thể xóa danh mục vì vẫn còn sản phẩm thuộc danh mục này.");
            } else {
                set_flash('success', "Đã xóa danh mục!");
            }
        }
        redirect(admin_url('categories'));
    }
}

$categories = $categoryModel->getAllWithProductCount();
$pageTitle = 'Quản lý Danh mục';
