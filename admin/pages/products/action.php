<?php
// admin/pages/products/action.php — Xử lý danh sách, tìm kiếm, lọc, phân trang, xoá và bật/tắt trạng thái sản phẩm
$title = 'Quản lý sản phẩm';
$action = get_param('action');

if ($action === 'toggle' || $action === 'toggle-status') {
    $id = (int)get_param('id');
    $current = db()->fetchOne("SELECT status FROM products WHERE id = ?", [$id]);
    if ($current) {
        $newStatus = ($current['status'] == 1) ? 0 : 1;
        db()->execute("UPDATE products SET status = ? WHERE id = ?", [$newStatus, $id]);
        set_flash('success', 'Đã cập nhật trạng thái sản phẩm!');
    }
    redirect(admin_url('products'));
}

if ($action === 'delete') {
    $id = (int)get_param('id');
    db()->execute("DELETE FROM product_toppings WHERE product_id = ?", [$id]);
    db()->execute("DELETE FROM products WHERE id = ?", [$id]);
    set_flash('success', 'Đã xóa sản phẩm thành công!');
    redirect(admin_url('products'));
}

// Lọc và tìm kiếm
$search = get_param('search');
$categoryId = get_param('category_id');
$status = get_param('status');
$p = (int)get_param('p', 1);
if ($p < 1) $p = 1;
$limit = 10;
$offset = ($p - 1) * $limit;

$where = ["1=1"];
$params = [];

if ($search !== '') {
    $where[] = "p.name LIKE ?";
    $params[] = '%' . $search . '%';
}

if ($categoryId !== '') {
    $where[] = "p.category_id = ?";
    $params[] = (int)$categoryId;
}

if ($status !== '') {
    $where[] = "p.status = ?";
    $params[] = (int)$status;
}

$whereSql = implode(' AND ', $where);

// Tổng số sản phẩm
$totalRow = db()->fetchOne("SELECT COUNT(*) as total FROM products p WHERE $whereSql", $params);
$total = (int)($totalRow['total'] ?? 0);
$totalPages = (int)ceil($total / $limit);

// Lấy danh sách sản phẩm theo trang
$sql = "SELECT p.*, c.name as category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        WHERE $whereSql 
        ORDER BY p.id DESC 
        LIMIT $limit OFFSET $offset";
$productsRaw = db()->fetchAll($sql, $params);

$products = [];
foreach ($productsRaw as $item) {
    $products[] = [
        'id'       => $item['id'],
        'name'     => $item['name'],
        'category' => $item['category_name'] ?? 'Chưa phân loại',
        'price'    => number_format($item['price'], 0, ',', '.') . ' ₫',
        'status'   => (string)$item['status'],
        'date'     => !empty($item['created_at']) ? date('d/m/Y', strtotime($item['created_at'])) : '',
        'image'    => product_image($item['image'])
    ];
}

$productPage = $p;
$categories = db()->fetchAll("SELECT * FROM categories ORDER BY name ASC");
