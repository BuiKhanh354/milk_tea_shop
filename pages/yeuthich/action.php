<?php
// pages/yeuthich/action.php — Xử lý danh sách yêu thích
$action = get_param('action', 'view');

if ($action === 'toggle' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    if (!isset($_SESSION['customer_id'])) {
        echo json_encode(array('status' => 'unauthorized'));
        exit;
    }

    $customerId = (int)$_SESSION['customer_id'];
    $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;

    $existing = db()->fetchOne("SELECT id FROM favorites WHERE customer_id = ? AND product_id = ?", array($customerId, $productId));
    if ($existing) {
        db()->execute("DELETE FROM favorites WHERE id = ?", array($existing['id']));
        echo json_encode(array('status' => 'success', 'action' => 'removed'));
    } else {
        db()->execute("INSERT INTO favorites (customer_id, product_id, created_at) VALUES (?, ?, NOW())", array($customerId, $productId));
        echo json_encode(array('status' => 'success', 'action' => 'added'));
    }
    exit;
}

$title = 'Sản phẩm yêu thích';

$favoritesList = array();
if (isset($_SESSION['customer_id'])) {
    $favoritesList = db()->fetchAll("SELECT p.*, c.name as category_name 
                                     FROM favorites f 
                                     JOIN products p ON f.product_id = p.id 
                                     LEFT JOIN categories c ON p.category_id = c.id 
                                     WHERE f.customer_id = ? AND p.status = 1 
                                     ORDER BY f.id DESC", array((int)$_SESSION['customer_id']));
}

