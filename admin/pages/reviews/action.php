<?php
// admin/pages/reviews/action.php — Quản lý đánh giá sản phẩm của Admin

// Xử lý các thao tác POST / GET (Ẩn/Hiện, Xóa)
$subAction = get_param('act', '');

if ($subAction === 'toggle_status') {
    $reviewId = (int)get_param('id', 0);
    $review = db()->fetchOne("SELECT id, status FROM reviews WHERE id = ?", [$reviewId]);
    if ($review) {
        $newStatus = $review['status'] == 1 ? 0 : 1;
        db()->execute("UPDATE reviews SET status = ? WHERE id = ?", [$newStatus, $reviewId]);
        set_flash('success', $newStatus == 1 ? 'Đã hiển thị công khai đánh giá.' : 'Đã ẩn đánh giá khỏi giao diện khách hàng.');
    } else {
        set_flash('error', 'Không tìm thấy đánh giá.');
    }
    redirect(admin_url('reviews'));
}

if ($subAction === 'delete') {
    $reviewId = (int)get_param('id', 0);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        validate_csrf();
        db()->execute("DELETE FROM reviews WHERE id = ?", [$reviewId]);
        set_flash('success', 'Đã xóa đánh giá thành công.');
        redirect(admin_url('reviews'));
    }
}

// Bộ lọc
$search = trim(get_param('search', ''));
$ratingFilter = get_param('rating', 'all');
$statusFilter = get_param('status', 'all');

$where = ["1=1"];
$params = [];

if ($search !== '') {
    $where[] = "(c.full_name LIKE ? OR p.name LIKE ? OR r.comment LIKE ?)";
    $like = '%' . $search . '%';
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
}

if ($ratingFilter !== 'all' && is_numeric($ratingFilter)) {
    $where[] = "r.rating = ?";
    $params[] = (int)$ratingFilter;
}

if ($statusFilter !== 'all') {
    $where[] = "r.status = ?";
    $params[] = (int)$statusFilter;
}

$whereClause = implode(" AND ", $where);

// Thống kê tổng quan
$stats = db()->fetchOne("SELECT 
    COUNT(id) as total_reviews,
    COALESCE(AVG(rating), 0) as avg_rating,
    SUM(CASE WHEN rating = 5 THEN 1 ELSE 0 END) as stars_5,
    SUM(CASE WHEN rating = 4 THEN 1 ELSE 0 END) as stars_4,
    SUM(CASE WHEN rating = 3 THEN 1 ELSE 0 END) as stars_3,
    SUM(CASE WHEN rating = 2 THEN 1 ELSE 0 END) as stars_2,
    SUM(CASE WHEN rating = 1 THEN 1 ELSE 0 END) as stars_1,
    SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as hidden_count,
    SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as visible_count
FROM reviews");

$totalReviewsCount = (int)($stats['total_reviews'] ?? 0);
$avgRating = round((float)($stats['avg_rating'] ?? 0), 1);
$hiddenCount = (int)($stats['hidden_count'] ?? 0);
$visibleCount = (int)($stats['visible_count'] ?? 0);

// Phân trang
$currentPageNum = isset($_GET['p']) ? (int)$_GET['p'] : 1;
if ($currentPageNum < 1) $currentPageNum = 1;
$limit = 15;

$countRow = db()->fetchOne("SELECT COUNT(r.id) as total 
                            FROM reviews r 
                            JOIN customers c ON r.customer_id = c.id 
                            JOIN products p ON r.product_id = p.id 
                            WHERE $whereClause", $params);
$totalFiltered = (int)($countRow['total'] ?? 0);
$totalPages = $totalFiltered > 0 ? (int)ceil($totalFiltered / $limit) : 1;
$offset = ($currentPageNum - 1) * $limit;

// Danh sách đánh giá
$sql = "SELECT r.*, c.full_name as customer_name, c.email as customer_email, c.phone as customer_phone,
               p.name as product_name, p.image as product_image, p.price as product_price,
               o.id as order_number
        FROM reviews r 
        JOIN customers c ON r.customer_id = c.id 
        JOIN products p ON r.product_id = p.id 
        LEFT JOIN orders o ON r.order_id = o.id 
        WHERE $whereClause 
        ORDER BY r.created_at DESC 
        LIMIT ? OFFSET ?";

$paramsWithLimit = array_merge($params, [(int)$limit, (int)$offset]);
$reviewsList = db()->fetchAll($sql, $paramsWithLimit);

$pageTitle = 'Quản lý đánh giá sản phẩm';
