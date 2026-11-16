<?php
// pages/product-detail/action.php — Xử lý dữ liệu chi tiết sản phẩm chuẩn gốc
require_once __DIR__ . '/../products/functions.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    redirect(url('products'));
}

$product = product_get_by_id($id);

if (!$product) {
    http_response_code(404);
    require __DIR__ . '/../404/index.php';
    exit;
}

$title = $product['name'];

// Lấy danh sách kích cỡ, topping, đường, đá
$sizes = product_get_sizes();
$toppings = product_get_toppings($product['id']);
$sugarOptions = product_get_sugar_options();
$iceOptions = product_get_ice_options();

// Đánh giá và số sao
$reviews = product_get_reviews($product['id']);
$product['review_count'] = count($reviews);
if (!empty($reviews)) {
    $sum = array_sum(array_column($reviews, 'rating'));
    $product['rating'] = round($sum / count($reviews), 1);
} else {
    $product['rating'] = 0.0;
}

// Sản phẩm liên quan
$relatedProducts = products_get_related($product['category_id'] ?? 1, $product['id'], 4);

// Kiểm tra yêu thích
$customerId = isset($_SESSION['customer_id']) ? (int)$_SESSION['customer_id'] : 0;
$isFavorited = product_is_favorited($customerId, $product['id']);

// Tồn kho tính từ định lượng nguyên liệu
$stockInfo = product_calculate_stock($product['id']);

// Danh sách tên nguyên liệu
$ingredientNames = product_get_ingredient_names($product['id']);
