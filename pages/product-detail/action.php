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

// Xử lý gửi đánh giá
if ($_SERVER['REQUEST_METHOD'] === 'POST' && get_param('action') === 'review') {
    if (!isset($_SESSION['customer_id'])) {
        set_flash('error', 'Vui lòng đăng nhập để gửi đánh giá sản phẩm.');
        redirect(url('login'));
    }

    $customerId = (int)$_SESSION['customer_id'];
    $rating = (int)post_param('rating', 5);
    $comment = trim((string)post_param('comment', ''));

    if ($rating < 1 || $rating > 5) {
        $rating = 5;
    }

    if (empty($comment)) {
        set_flash('error', 'Vui lòng nhập nội dung đánh giá của bạn.');
    } else {
        // Tìm xem khách đã có đơn hàng nào chứa sản phẩm này không (để gán order_id)
        $orderItem = db()->fetchOne("SELECT od.order_id 
                                     FROM order_details od 
                                     JOIN orders o ON od.order_id = o.id 
                                     WHERE o.customer_id = ? AND od.product_id = ? 
                                     ORDER BY o.id DESC LIMIT 1", [$customerId, $id]);
        $orderId = $orderItem ? (int)$orderItem['order_id'] : null;

        db()->execute("INSERT INTO reviews (customer_id, product_id, order_id, rating, comment, status, created_at) 
                       VALUES (?, ?, ?, ?, ?, 1, NOW())", 
                       [$customerId, $id, $orderId, $rating, $comment]);

        set_flash('success', 'Cảm ơn bạn đã gửi đánh giá cho món ' . $product['name'] . '!');
    }

    redirect(url('product-detail', ['id' => $id]) . '#reviews-section');
}

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
