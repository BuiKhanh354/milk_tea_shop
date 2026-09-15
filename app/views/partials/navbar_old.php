<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$is_logged_in = isset($_SESSION['customer_id']);
$customer_name = $is_logged_in ? $_SESSION['customer_name'] : '';
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
                    <a class="nav-link" href="products.php">Sản phẩm</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="stores.php">Cửa hàng</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="about.php">Giới thiệu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.php">Liên hệ</a>
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
                        <a href="profile.php" class="text-forest text-decoration-none fs-6 fw-bold transition-fast hover-caramel dropdown-toggle d-flex align-items-center gap-2" id="accountDropdown" aria-expanded="false">
                            <i class="fa-regular fa-user"></i>
                            <span class="d-none d-lg-inline" style="font-size: 0.9rem;">Xin chào, <?= htmlspecialchars($customer_name) ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-0 custom-dropdown" aria-labelledby="accountDropdown">
                            <li><a class="dropdown-item py-2" href="profile.php"><i class="fa-regular fa-id-card me-2"></i> Thông tin cá nhân</a></li>
                            <li><a class="dropdown-item py-2" href="orders.php"><i class="fa-solid fa-clock-rotate-left me-2"></i> Đơn đã mua</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item py-2 text-danger" href="logout.php"><i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Đăng xuất</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="text-forest text-decoration-none fs-5 transition-fast hover-caramel" title="Đăng nhập">
                        <i class="fa-regular fa-user"></i>
                    </a>
                <?php endif; ?>

                <!-- Cart -->
                <a href="cart.php" class="text-forest text-decoration-none fs-5 position-relative transition-fast hover-caramel">
                    <i class="fa-solid fa-bag-shopping"></i>
                    <!-- <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-caramel border border-light" style="font-size: 0.6rem; transform: translate(-30%, -30%) !important;">
                        2
                        <span class="visually-hidden">sản phẩm trong giỏ</span>
                    </span> -->
                </a>
            </div>
        </div>

    </div>
</nav>

<!-- Spacer to prevent content overlapping with fixed navbar -->
<div style="height: 80px;"></div>
