<?php
// pages/yeuthich/action.php — Xử lý danh sách yêu thích chuẩn gốc VAA THÉ
$action = get_param('action', 'view');

if ($action === 'toggle' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');
    if (!isset($_SESSION['customer_id'])) {
        echo json_encode([
            'status'        => 'unauthorized',
            'success'       => false,
            'require_login' => true,
            'message'       => 'Vui lòng đăng nhập để thêm vào yêu thích.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $customerId = (int)$_SESSION['customer_id'];
    $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;

    if ($productId <= 0) {
        echo json_encode([
            'status'  => 'error',
            'success' => false,
            'message' => 'Sản phẩm không hợp lệ'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $existing = db()->fetchOne("SELECT product_id FROM favorites WHERE customer_id = ? AND product_id = ?", [$customerId, $productId]);
    if ($existing) {
        db()->execute("DELETE FROM favorites WHERE customer_id = ? AND product_id = ?", [$customerId, $productId]);
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

if (!isset($_SESSION['customer_id'])) {
    redirect(url('login'));
}

$customerId = (int)$_SESSION['customer_id'];
$title = 'Sản phẩm yêu thích';

$favorites = db()->fetchAll("SELECT p.*, c.name as category_name 
                             FROM favorites f 
                             JOIN products p ON f.product_id = p.id 
                             LEFT JOIN categories c ON p.category_id = c.id 
                             WHERE f.customer_id = ? AND p.status = 1 
                             ORDER BY f.created_at DESC", [$customerId]);

$favoritesList = $favorites;
