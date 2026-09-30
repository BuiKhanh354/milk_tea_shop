<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg fixed-top vaa-header">
    <div class="container-fluid px-4 px-lg-5">

        <!-- LEFT: BRAND LOGO -->
        <a class="navbar-brand vaa-logo d-flex align-items-center gap-2" href="index.php?route=home">
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
                    <a class="nav-link" href="index.php?route=home">Trang chủ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?route=products">Sản phẩm</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#promotions">Cửa hàng</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?route=about">Giới thiệu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#contact">Liên hệ</a>
                </li>
            </ul>

            <!-- Right Actions -->
            <div class="d-flex align-items-center justify-content-center gap-4 mt-3 mt-lg-0">
                <!-- Search -->
                <a href="#" class="text-forest text-decoration-none fs-5 transition-fast hover-caramel">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </a>
                <!-- Account -->
                <a href="index.php?route=login" class="text-forest text-decoration-none fs-5 transition-fast hover-caramel">
                    <i class="fa-regular fa-user"></i>
                </a>
                <a class="nav-link" href="index.php?route=logout">
                        Đăng xuất
                </a>
                <!-- Cart -->
                <?php
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $cart_count = 0;
                if(isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
                    foreach($_SESSION['cart'] as $item) {
                        $cart_count += $item['quantity'];
                    }
                }
                ?>
                <a href="index.php?route=cart"
                    class="text-forest text-decoration-none fs-5 position-relative transition-fast hover-caramel">
                    <i class="fa-solid fa-bag-shopping"></i>
                    <span id="cart-badge"
                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-caramel text-dark border border-light"
                        style="font-size: 0.6rem; transform: translate(-30%, -30%) !important;">
                        <?= $cart_count ?>
                        <span class="visually-hidden">sản phẩm trong giỏ</span>
                    </span>
                </a>
            </div>
        </div>

    </div>
</nav>

<!-- Spacer to prevent content overlapping with fixed navbar -->
<div style="height: 80px;"></div>
