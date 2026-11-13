<?php
// pages/product-detail/action.php — Xử lý dữ liệu chi tiết sản phẩm
require_once __DIR__ . '/../products/functions.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = product_get_by_id($id);

if (!$product) {
    http_response_code(404);
    require __DIR__ . '/../404/index.php';
    exit;
}

$title = $product['name'];

// Lấy Topping, Size, Sugar, Ice
$toppings = product_get_toppings();
$sizes = product_get_sizes();
$sugarLevels = product_get_sugar_levels();
$iceLevels = product_get_ice_levels();

// Sản phẩm liên quan
$relatedProducts = products_get_related($product['category_id'], $product['id'], 4);

// Kiểm tra yêu thích
$isFavorited = false;
if (isset($_SESSION['customer_id'])) {
    $fav = db()->fetchOne("SELECT id FROM favorites WHERE customer_id = ? AND product_id = ?", array((int)$_SESSION['customer_id'], (int)$product['id']));
    $isFavorited = !empty($fav);
}

