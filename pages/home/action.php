<?php
// pages/home/action.php — Chuẩn bị dữ liệu cho trang chủ
require_once __DIR__ . '/../products/functions.php';

$title = 'Trang chủ';

// Lấy 4 sản phẩm bán chạy / mới nhất cho Best Sellers
$bestSellers = db()->fetchAll("SELECT * FROM products WHERE status = 1 ORDER BY id DESC LIMIT 4");

$favorites = array();
if (isset($_SESSION['customer_id'])) {
    $favRows = db()->fetchAll("SELECT product_id FROM favorites WHERE customer_id = ?", array((int)$_SESSION['customer_id']));
    $favorites = array_column($favRows, 'product_id');
}
