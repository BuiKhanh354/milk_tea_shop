<?php
// admin/layouts/header.php
$pageTitle = $title ?? 'Quản Trị Hệ Thống';
$currentPage = get_param('page', 'dashboard');
$adminName = $_SESSION['full_name'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> | Admin VAA THÉ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= e(asset('assets/css/style.css')) ?>">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-forest sticky-top shadow-sm">
    <div class="container-fluid px-4">
        <a class="navbar-brand d-flex align-items-center gap-2" href="<?= e(admin_url('dashboard')) ?>">
            <i class="fa-solid fa-paper-plane text-caramel"></i>
            <span class="fw-bold">VAA THÉ · Admin</span>
        </a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'dashboard' ? 'active fw-bold' : '' ?>" href="<?= e(admin_url('dashboard')) ?>">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= in_array($currentPage, ['products', 'product-create', 'product-edit']) ? 'active fw-bold' : '' ?>" href="<?= e(admin_url('products')) ?>">Sản Phẩm</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= in_array($currentPage, ['orders', 'order-detail']) ? 'active fw-bold' : '' ?>" href="<?= e(admin_url('orders')) ?>">Đơn Hàng</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-warning" href="<?= e(url('home')) ?>" target="_blank">Xem Website <i class="fa-solid fa-arrow-up-right-from-square small ms-1"></i></a>
                </li>
            </ul>
            <div class="d-flex align-items-center gap-3 text-white">
                <span><i class="fa-regular fa-user me-1"></i> <?= e($adminName) ?></span>
                <a href="<?= e(admin_url('logout')) ?>" class="btn btn-sm btn-outline-light rounded-pill px-3">Đăng xuất</a>
            </div>
        </div>
    </div>
</nav>

<div class="container-fluid px-4 py-4">

