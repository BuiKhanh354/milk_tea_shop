<?php
// pages/products/action.php — Chuẩn bị dữ liệu cho trang danh sách sản phẩm chuẩn gốc
require_once __DIR__ . '/functions.php';

$title = 'Sản Phẩm';

$page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
if ($page < 1) $page = 1;

$currentCategory = isset($_GET['category_id']) && $_GET['category_id'] !== '' ? $_GET['category_id'] : 'all';
$currentSearch = isset($_GET['search']) ? trim($_GET['search']) : '';
$currentSort = isset($_GET['sort']) ? trim($_GET['sort']) : 'default';

$categoriesList = products_get_categories();

// Xử lý bộ lọc và sắp xếp
$where = array("p.status = 1");
$params = array();

if ($currentCategory !== 'all' && is_numeric($currentCategory)) {
    $where[] = "p.category_id = ?";
    $params[] = (int)$currentCategory;
}

if ($currentSearch !== '') {
    $where[] = "p.name LIKE ?";
    $params[] = '%' . $currentSearch . '%';
}

$whereClause = implode(" AND ", $where);

// Tính tổng số lượng
$countSql = "SELECT COUNT(*) as total FROM products p WHERE $whereClause";
$countRow = db()->fetchOne($countSql, $params);
$total = (int)($countRow['total'] ?? 0);
$limit = 12;
$totalPages = $total > 0 ? (int)ceil($total / $limit) : 1;
$offset = ($page - 1) * $limit;

// Order By
$orderBy = "p.id DESC";
if ($currentSort === 'price_asc') {
    $orderBy = "p.price ASC";
} elseif ($currentSort === 'price_desc') {
    $orderBy = "p.price DESC";
} elseif ($currentSort === 'name_asc') {
    $orderBy = "p.name ASC";
}

$sql = "SELECT p.*, c.name as category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        WHERE $whereClause 
        ORDER BY $orderBy 
        LIMIT ? OFFSET ?";

$paramsWithLimit = array_merge($params, array((int)$limit, (int)$offset));
$productsList = db()->fetchAll($sql, $paramsWithLimit);

$currentPage = $page;

// Danh sách yêu thích của người dùng nếu đã đăng nhập
$favorites = array();
if (isset($_SESSION['customer_id'])) {
    $favRows = db()->fetchAll("SELECT product_id FROM favorites WHERE customer_id = ?", array((int)$_SESSION['customer_id']));
    $favorites = array_column($favRows, 'product_id');
}
