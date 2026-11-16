<?php
// pages/yeuthich/action.php — Xử lý danh sách yêu thích
$action = get_param('action', 'view');

if ($action === 'toggle' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');
    if (!isset($_SESSION['customer_id'])) {
        echo json_encode([
            'status'        => 'unauthorized',
            'success'       => false,
            'require_login' => true,
            'message'       => 'Vui lòng đăng nhập để lưu sản phẩm yêu thích.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $customerId = (int)$_SESSION['customer_id'];
    $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;

    $existing = db()->fetchOne("SELECT id FROM favorites WHERE customer_id = ? AND product_id = ?", [$customerId, $productId]);
    if ($existing) {
        db()->execute("DELETE FROM favorites WHERE id = ?", [$existing['id']]);
        echo json_encode([
            'status'       => 'success',
            'success'      => true,
            'action'       => 'removed',
            'is_favorited' => false,
            'message'      => 'Đã bỏ yêu thích'
        ], JSON_UNESCAPED_UNICODE);
    } else {
        db()->execute("INSERT INTO favorites (customer_id, product_id, created_at) VALUES (?, ?, NOW())", [$customerId, $productId]);
        echo json_encode([
            'status'       => 'success',
            'success'      => true,
            'action'       => 'added',
            'is_favorited' => true,
            'message'      => 'Đã thêm vào yêu thích'
        ], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

$title = 'Sản phẩm yêu thích';

$favoritesList = [];
if (isset($_SESSION['customer_id'])) {
    $favoritesList = db()->fetchAll("SELECT p.*, c.name as category_name 
                                     FROM favorites f 
                                     JOIN products p ON f.product_id = p.id 
                                     LEFT JOIN categories c ON p.category_id = c.id 
                                     WHERE f.customer_id = ? AND p.status = 1 
                                     ORDER BY f.id DESC", [(int)$_SESSION['customer_id']]);
}
