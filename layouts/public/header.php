<?php
// layouts/public/header.php — Phần Header chung chuẩn minishop
$pageTitle = $title ?? 'VAA THÉ TEA & COFFEE';
$currentPage = get_param('page', 'home');

$is_logged_in = isset($_SESSION['customer_id']);
$is_admin = isset($_SESSION['user_id']);
$customer_name = $is_logged_in ? $_SESSION['customer_name'] : '';
$admin_name = $is_admin ? $_SESSION['full_name'] : '';

$cart_count = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cart_count += $item['quantity'] ?? 1;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> | VAA THÉ</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= e(asset('assets/css/style.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('assets/css/products.css')) ?>">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg fixed-top vaa-header">
    <div class="container-fluid px-4 px-lg-5">

        <!-- BRAND LOGO -->
        <a class="navbar-brand vaa-logo d-flex align-items-center gap-2" href="<?= e(url('home')) ?>">
            <i class="fa-solid fa-paper-plane text-caramel"></i>
            <span>VAA THÉ</span>
        </a>

        <!-- MOBILE TOGGLE -->
        <button class="navbar-toggler border-0 shadow-none text-forest" type="button" data-bs-toggle="collapse"
            data-bs-target="#vaaNavbar" aria-controls="vaaNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fa-solid fa-bars fs-4"></i>
        </button>

        <!-- MENU & ACTIONS -->
        <div class="collapse navbar-collapse" id="vaaNavbar">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 text-center text-lg-start mt-4 mt-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'home' ? 'active' : '' ?>" href="<?= e(url('home')) ?>">Trang chủ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'products' ? 'active' : '' ?>" href="<?= e(url('products')) ?>">Sản phẩm</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'stores' ? 'active' : '' ?>" href="<?= e(url('stores')) ?>">Cửa hàng</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'about' ? 'active' : '' ?>" href="<?= e(url('about')) ?>">Giới thiệu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'contact' ? 'active' : '' ?>" href="<?= e(url('contact')) ?>">Liên hệ</a>
                </li>
            </ul>

            <!-- Right Actions -->
            <div class="d-flex align-items-center justify-content-center gap-4 mt-3 mt-lg-0">
                <!-- Favorites -->
                <a href="<?= e(url('yeuthich')) ?>" class="text-forest text-decoration-none fs-5 transition-fast hover-caramel position-relative" title="Yêu thích">
                    <i class="fa-regular fa-heart"></i>
                </a>

                <!-- Account -->
                <?php if ($is_logged_in): ?>
                    <div class="dropdown hover-dropdown">
                        <a href="<?= e(url('profile')) ?>" class="text-forest text-decoration-none fs-6 fw-bold transition-fast hover-caramel dropdown-toggle d-flex align-items-center gap-2" id="accountDropdown" aria-expanded="false">
                            <i class="fa-regular fa-user"></i>
                            <span class="d-none d-lg-inline">Xin chào, <?= e($customer_name) ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-0 custom-dropdown" aria-labelledby="accountDropdown">
                            <li><a class="dropdown-item py-2" href="<?= e(url('profile')) ?>"><i class="fa-regular fa-id-card me-2"></i> Thông tin cá nhân</a></li>
                            <li><a class="dropdown-item py-2" href="<?= e(url('orders')) ?>"><i class="fa-solid fa-clock-rotate-left me-2"></i> Đơn đã mua</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item py-2 text-danger" href="<?= e(url('logout')) ?>"><i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Đăng xuất</a></li>
                        </ul>
                    </div>
                <?php elseif ($is_admin): ?>
                    <div class="dropdown hover-dropdown">
                        <a href="<?= e(admin_url('dashboard')) ?>" class="text-forest text-decoration-none fs-6 fw-bold transition-fast hover-caramel dropdown-toggle d-flex align-items-center gap-2" id="adminDropdown" aria-expanded="false">
                            <i class="fa-solid fa-user-tie"></i>
                            <span class="d-none d-lg-inline"><?= e($admin_name) ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-0 custom-dropdown" aria-labelledby="adminDropdown">
                            <li><a class="dropdown-item py-2" href="<?= e(admin_url('dashboard')) ?>"><i class="fa-solid fa-chart-pie me-2"></i> Trang quản trị</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item py-2 text-danger" href="<?= e(admin_url('logout')) ?>"><i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Đăng xuất</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="<?= e(url('login')) ?>" class="text-forest text-decoration-none fs-5 transition-fast hover-caramel" title="Đăng nhập">
                        <i class="fa-regular fa-user"></i>
                    </a>
                <?php endif; ?>

                <!-- Cart -->
                <a href="<?= e(url('cart')) ?>" class="text-forest text-decoration-none fs-5 position-relative transition-fast hover-caramel" title="Giỏ hàng">
                    <i class="fa-solid fa-bag-shopping"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-caramel text-dark" id="cart-badge">
                        <?= $cart_count ?>
                        <span class="visually-hidden">sản phẩm trong giỏ</span>
                    </span>
                </a>
            </div>
        </div>
    </div>
</nav>

<div class="main-content-wrapper" style="padding-top: 80px;">

