<?php
// pages/products/action.php — Chuẩn bị dữ liệu cho trang danh sách sản phẩm
require_once __DIR__ . '/functions.php';

$title = 'Danh sách sản phẩm';

$page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
if ($page < 1) $page = 1;

$categoryId = isset($_GET['category']) && $_GET['category'] !== '' ? (int)$_GET['category'] : null;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$filters = array();
if ($categoryId) {
    $filters['category_id'] = $categoryId;
}
if ($search !== '') {
    $filters['search'] = $search;
}

$categories = products_get_categories();
$paginated = products_get_paginated($page, 8, $filters);

$productsList = $paginated['data'];
$totalPages = $paginated['total_pages'];
$currentPageNumber = $paginated['page'];
$totalProducts = $paginated['total'];

// Danh sách yêu thích của người dùng nếu đã đăng nhập
$favorites = array();
if (isset($_SESSION['customer_id'])) {
    $favRows = db()->fetchAll("SELECT product_id FROM favorites WHERE customer_id = ?", array((int)$_SESSION['customer_id']));
    $favorites = array_column($favRows, 'product_id');
}

