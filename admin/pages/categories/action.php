<?php
// admin/pages/categories/action.php — Quản lý danh mục
$action = get_param('action', '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'store') {
        $name = post_param('name');
        $description = post_param('description');
        if (!empty($name)) {
            try {
                db()->execute("INSERT INTO categories (name, description) VALUES (?, ?)", [$name, $description]);
                set_flash('success', "Đã thêm danh mục mới thành công!");
            } catch (Exception $e) {
                set_flash('error', "Tên danh mục đã tồn tại hoặc có lỗi xảy ra!");
            }
        }
        redirect(admin_url('categories'));
    }

    if ($action === 'update') {
        $id = post_param('id');
        $name = post_param('name');
        $description = post_param('description');
        if ($id && !empty($name)) {
            try {
                db()->execute("UPDATE categories SET name = ?, description = ? WHERE id = ?", [$name, $description, $id]);
                set_flash('success', "Cập nhật danh mục thành công!");
            } catch (Exception $e) {
                set_flash('error', "Tên danh mục đã tồn tại hoặc có lỗi xảy ra!");
            }
        }
        redirect(admin_url('categories'));
    }

    if ($action === 'delete') {
        $id = post_param('id');
        if ($id) {
            $check = db()->fetchOne("SELECT COUNT(*) as count FROM products WHERE category_id = ?", [$id]);
            if ($check && (int)$check['count'] > 0) {
                set_flash('error', "Không thể xóa danh mục vì vẫn còn sản phẩm thuộc danh mục này.");
            } else {
                db()->execute("DELETE FROM categories WHERE id = ?", [$id]);
                set_flash('success', "Đã xóa danh mục!");
            }
        }
        redirect(admin_url('categories'));
    }
}

$categories = db()->fetchAll("SELECT c.*, COUNT(p.id) as products_count 
    FROM categories c 
    LEFT JOIN products p ON c.id = p.category_id 
    GROUP BY c.id 
    ORDER BY c.id ASC");
$pageTitle = 'Quản lý Danh mục';
