<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$is_logged_in = isset($_SESSION['customer_id']);
$is_admin = isset($_SESSION['user_id']);
$customer_name = $is_logged_in ? $_SESSION['customer_name'] : '';
$admin_name = $is_admin ? $_SESSION['full_name'] : '';
?>
<!-- NAVBAR (Old UI style converted to PHP) -->
<nav class="navbar navbar-expand-lg fixed-top vaa-header">
    <div class="container-fluid px-4 px-lg-5">

        <!-- LEFT: BRAND LOGO -->
        <a class="navbar-brand vaa-logo d-flex align-items-center gap-2" href="index.php">
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
                    <a class="nav-link" href="index.php">Trang chủ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?route=products">Sản phẩm</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?route=stores">Cửa hàng</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?route=about">Giới thiệu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?route=contact">Liên hệ</a>
                </li>
            </ul>

            <!-- Right Actions -->
            <div class="d-flex align-items-center justify-content-center gap-4 mt-3 mt-lg-0">
                <!-- Search -->
                <a href="#" class="text-forest text-decoration-none fs-5 transition-fast hover-caramel">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </a>
                
                <!-- Account -->
                <?php if ($is_logged_in): ?>
                    <div class="dropdown hover-dropdown">
                        <a href="index.php?route=profile" class="text-forest text-decoration-none fs-6 fw-bold transition-fast hover-caramel dropdown-toggle d-flex align-items-center gap-2" id="accountDropdown" aria-expanded="false">
                            <i class="fa-regular fa-user"></i>
                            <span class="d-none d-lg-inline global-style-33dd45" >Xin chào, <?= htmlspecialchars($customer_name) ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-0 custom-dropdown" aria-labelledby="accountDropdown">
                            <li><a class="dropdown-item py-2" href="index.php?route=profile"><i class="fa-regular fa-id-card me-2"></i> Thông tin cá nhân</a></li>
                            <li><a class="dropdown-item py-2" href="index.php?route=orders"><i class="fa-solid fa-clock-rotate-left me-2"></i> Đơn đã mua</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item py-2 text-danger" href="index.php?route=logout"><i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Đăng xuất</a></li>
                        </ul>
                    </div>
                <?php elseif ($is_admin): ?>
                    <div class="dropdown hover-dropdown">
                        <a href="admin.php?route=dashboard" class="text-forest text-decoration-none fs-6 fw-bold transition-fast hover-caramel dropdown-toggle d-flex align-items-center gap-2" id="adminDropdown" aria-expanded="false">
                            <i class="fa-solid fa-user-tie"></i>
                            <span class="d-none d-lg-inline global-style-33dd45" ><?= htmlspecialchars($admin_name) ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-0 custom-dropdown" aria-labelledby="adminDropdown">
                            <li><a class="dropdown-item py-2" href="admin.php?route=dashboard"><i class="fa-solid fa-chart-pie me-2"></i> Trang quản trị</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item py-2 text-danger" href="admin.php?route=logout"><i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Đăng xuất</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="index.php?route=login" class="text-forest text-decoration-none fs-5 transition-fast hover-caramel" title="Đăng nhập">
                        <i class="fa-regular fa-user"></i>
                    </a>
                <?php endif; ?>

                <!-- Cart -->
                <?php
                $cart_count = 0;
                if(isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
                    foreach($_SESSION['cart'] as $item) {
                        $cart_count += $item['quantity'];
                    }
                }
                ?>
                <a href="index.php?route=cart" class="text-forest text-decoration-none fs-5 position-relative transition-fast hover-caramel">
                    <i class="fa-solid fa-bag-shopping"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-caramel text-dark global-style-559096" id="cart-badge" >
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
