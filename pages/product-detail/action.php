<?php
// pages/product-detail/action.php — Xử lý dữ liệu chi tiết sản phẩm chuẩn gốc
require_once __DIR__ . '/../products/functions.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = product_get_by_id($id);

if (!$product) {
    http_response_code(404);
    require __DIR__ . '/../404/index.php';
    exit;
}

$title = $product['name'];

// Thêm các trường mặc định nếu chưa có
$product['rating'] = 4.8;
$product['review_count'] = 42;

// Lấy Topping, Size, Sugar, Ice
$toppings = product_get_toppings();
$sizes = product_get_sizes();
$sugarLevels = product_get_sugar_levels();
$iceLevels = product_get_ice_levels();

// Đánh giá mẫu chuẩn
$reviews = array(
    array('name' => 'Nguyễn Minh Anh', 'date' => '12/10/2026', 'rating' => 5, 'comment' => 'Vị trà thơm tự nhiên, độ ngọt vừa vặn rất hợp gu mình.'),
    array('name' => 'Trần Hoàng Long', 'date' => '05/10/2026', 'rating' => 5, 'comment' => 'Trân châu mềm dai, uống không bị gắt cổ. Sẽ ủng hộ dài dài!'),
    array('name' => 'Lê Phương Thảo', 'date' => '28/09/2026', 'rating' => 4, 'comment' => 'Đóng gói cẩn thận, ly giữ nhiệt tốt và giao hàng nhanh.'),
);

// Sản phẩm liên quan
$relatedProducts = products_get_related($product['category_id'], $product['id'], 4);

// Kiểm tra yêu thích
$isFavorited = false;
if (isset($_SESSION['customer_id'])) {
    $fav = db()->fetchOne("SELECT id FROM favorites WHERE customer_id = ? AND product_id = ?", array((int)$_SESSION['customer_id'], (int)$product['id']));
    $isFavorited = !empty($fav);
}
