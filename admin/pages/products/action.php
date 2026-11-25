<?php
// admin/pages/products/action.php — Quản lý sản phẩm (OOP)
$title = 'Quản lý sản phẩm';
$action = get_param('action');
$productModel = new Product();
$categoryModel = new Category();

if ($action === 'toggle' || $action === 'toggle-status') {
    $id = (int)get_param('id');
    if ($productModel->toggleStatus($id) !== null) {
        set_flash('success', 'Đã cập nhật trạng thái sản phẩm!');
    }
    redirect(admin_url('products'));
}

if ($action === 'delete') {
    $id = (int)get_param('id');
    $productModel->delete($id);
    set_flash('success', 'Đã xóa sản phẩm thành công!');
    redirect(admin_url('products'));
}

$search = get_param('search');
$categoryId = get_param('category_id');
$status = get_param('status');
$p = max(1, (int)get_param('p', 1));
$limit = 10;

$result = $productModel->getPaginated($p, $limit, [
    'search'      => $search,
    'category_id' => $categoryId,
    'status'      => $status,
], false);

$total = $result['total'];
$totalPages = $result['total_pages'];

$products = [];
foreach ($result['data'] as $item) {
    $products[] = [
        'id'       => $item['id'],
        'name'     => $item['name'],
        'category' => $item['category_name'] ?? 'Chưa phân loại',
        'price'    => number_format($item['price'], 0, ',', '.') . ' ₫',
        'status'   => (string)$item['status'],
        'date'     => !empty($item['created_at']) ? date('d/m/Y', strtotime($item['created_at'])) : '',
        'image'    => product_image($item['image']),
    ];
}

$productPage = $p;
$categories = $categoryModel->all('name ASC');
