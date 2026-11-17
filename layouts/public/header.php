<?php
// layouts/public/header.php — Header chuẩn giao diện VAA THÉ gốc
$pageTitle = $title ?? 'VAA THÉ';
$currentActiveNav = $page ?? get_param('page', get_param('route', 'home'));
$isAuthPage = in_array($currentActiveNav, array('login', 'register'), true);

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
    <?= csrf_meta() ?>
    <title><?= e($pageTitle) ?> - VAA THÉ</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400;1,500&family=Inter:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS chuẩn gốc -->
    <link rel="stylesheet" href="<?= e(asset('assets/css/style.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('assets/css/intro.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('assets/css/home.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('assets/css/products.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('assets/css/product-detail.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('assets/css/about.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('assets/css/store.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('assets/css/account.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('assets/css/global.css')) ?>">
    <?php if ($isAuthPage): ?>
    <link rel="stylesheet" href="<?= e(asset('assets/css/auth.css')) ?>">
    <?php endif; ?>
</head>
<body class="bg-ivory<?= $isAuthPage ? ' auth-page' : '' ?>">

<!-- NAVBAR (Old UI style converted to PHP) -->
<nav class="navbar navbar-expand-lg fixed-top vaa-header">
    <div class="container-fluid px-4 px-lg-5">

        <!-- LEFT: BRAND LOGO -->
        <a class="navbar-brand vaa-logo d-flex align-items-center gap-2" href="<?= e(url('home')) ?>">
            <i class="fa-solid fa-paper-plane text-caramel"></i>
            <span>VAA THÉ</span>
        </a>

        <!-- MOBILE TOGGLE -->
        <button class="navbar-toggler border-0 shadow-none text-forest" type="button" data-bs-toggle="collapse"
            data-bs-target="#vaaNavbar" aria-controls="vaaNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fa-solid fa-bars fs-4"></i>
        </button>

        <!-- CENTER: MENU & RIGHT: ACTIONS -->
        <div class="collapse navbar-collapse" id="vaaNavbar">
            <!-- Center Menu -->
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 text-center text-lg-start mt-4 mt-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= in_array($currentActiveNav, ['home', ''], true) ? 'active' : '' ?>" href="<?= e(url('home')) ?>">Trang chủ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= in_array($currentActiveNav, ['products', 'product-detail', 'product_detail'], true) ? 'active' : '' ?>" href="<?= e(url('products')) ?>">Sản phẩm</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentActiveNav === 'stores' ? 'active' : '' ?>" href="<?= e(url('stores')) ?>">Cửa hàng</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentActiveNav === 'about' ? 'active' : '' ?>" href="<?= e(url('about')) ?>">Giới thiệu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentActiveNav === 'contact' ? 'active' : '' ?>" href="<?= e(url('contact')) ?>">Liên hệ</a>
                </li>
            </ul>

            <!-- Right Actions -->
            <div class="d-flex align-items-center justify-content-center gap-4 mt-3 mt-lg-0">
                <!-- Search -->
                <a href="<?= e(url('products')) ?>" class="text-forest text-decoration-none fs-5 transition-fast hover-caramel" title="Tìm kiếm">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </a>
                
                <!-- Account -->
                <?php if ($is_logged_in): ?>
                    <div class="dropdown hover-dropdown">
                        <a href="<?= e(url('profile')) ?>" class="text-forest text-decoration-none fs-6 fw-bold transition-fast hover-caramel dropdown-toggle d-flex align-items-center gap-2" id="accountDropdown" aria-expanded="false">
                            <i class="fa-regular fa-user"></i>
                            <span class="d-none d-lg-inline global-style-33dd45">Xin chào, <?= e($customer_name) ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-0 custom-dropdown" aria-labelledby="accountDropdown">
                            <li><a class="dropdown-item py-2" href="<?= e(url('profile')) ?>"><i class="fa-regular fa-id-card me-2"></i> Thông tin cá nhân</a></li>
                            <li><a class="dropdown-item py-2" href="<?= e(url('orders')) ?>"><i class="fa-solid fa-clock-rotate-left me-2"></i> Đơn đã mua</a></li>
                            <li><a class="dropdown-item py-2" href="<?= e(url('yeuthich')) ?>"><i class="fa-regular fa-heart me-2"></i> Sản phẩm yêu thích</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item py-2 text-danger" href="<?= e(url('logout')) ?>"><i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Đăng xuất</a></li>
                        </ul>
                    </div>
                <?php elseif ($is_admin): ?>
                    <div class="dropdown hover-dropdown">
                        <a href="<?= e(admin_url('dashboard')) ?>" class="text-forest text-decoration-none fs-6 fw-bold transition-fast hover-caramel dropdown-toggle d-flex align-items-center gap-2" id="adminDropdown" aria-expanded="false">
                            <i class="fa-solid fa-user-tie"></i>
                            <span class="d-none d-lg-inline global-style-33dd45"><?= e($admin_name) ?></span>
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
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-caramel text-dark global-style-559096" id="cart-badge">
                        <?= $cart_count ?>
                        <span class="visually-hidden">sản phẩm trong giỏ</span>
                    </span>
                </a>
            </div>
        </div>

    </div>
</nav>

<!-- Spacer to prevent content overlapping with fixed navbar -->
<div class="global-style-849459"></div>
