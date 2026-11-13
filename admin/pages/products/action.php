<?php
// admin/pages/products/action.php — Xử lý danh sách, ẩn/hiện, xoá sản phẩm
$action = get_param('action');

if ($action === 'toggle-status') {
    $id = (int)get_param('id');
    $current = db()->fetchOne("SELECT status FROM products WHERE id = ?", array($id));
    if ($current) {
        $newStatus = ($current['status'] == 1) ? 0 : 1;
        db()->execute("UPDATE products SET status = ? WHERE id = ?", array($newStatus, $id));
    }
    redirect(admin_url('products'));
}

if ($action === 'delete') {
    $id = (int)get_param('id');
    db()->execute("DELETE FROM products WHERE id = ?", array($id));
    redirect(admin_url('products'));
}

$title = 'Quản lý sản phẩm';

$products = db()->fetchAll("SELECT p.*, c.name as category_name 
                            FROM products p 
                            LEFT JOIN categories c ON p.category_id = c.id 
                            ORDER BY p.id DESC");

