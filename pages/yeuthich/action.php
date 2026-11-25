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

    $favoriteModel = new Favorite();
    $resultAction = $favoriteModel->toggle($customerId, $productId);
    if ($resultAction === 'removed') {
        echo json_encode([
            'status'       => 'success',
            'success'      => true,
            'action'       => 'removed',
            'is_favorited' => false,
            'message'      => 'Đã bỏ yêu thích'
        ], JSON_UNESCAPED_UNICODE);
    } else {
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

$favoriteModel = new Favorite();
$favorites = $favoriteModel->getByCustomer($customerId);

$favoritesList = $favorites;
