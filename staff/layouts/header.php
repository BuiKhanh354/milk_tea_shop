<?php
// staff/layouts/header.php — Layout giao diện chuẩn Nhân viên (Staff Layout) đồng bộ phong cách VAA THÉ
$pageTitle = $pageTitle ?? ($title ?? 'Khu vực nhân viên');
$currentPage = get_param('page', 'dashboard');
$staffName = $_SESSION['full_name'] ?? 'Nhân viên';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="<?= e(asset('assets/images/VAU-logo-text.svg')) ?>">
    <title><?= e($pageTitle) ?> - VAA THÉ Nhân viên</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400;1,500&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= e(asset('assets/css/style.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('assets/css/admin.css')) ?>">
</head>
<body class="admin-body staff-body">

    <!-- Desktop Sidebar (Đồng bộ chuẩn phong cách VAA THÉ) -->
    <aside class="admin-sidebar d-none d-lg-flex flex-column">
        <div class="sidebar-brand px-4 py-4 text-center">
            <a href="<?= e(url('home')) ?>" class="text-decoration-none">
                <h2 class="font-serif fw-bold text-forest mb-1">VAA THÉ</h2>
            </a>
            <p class="small text-muted mb-0 fst-italic">GOOD TEA, GOOD MOOD</p>
            <span class="badge rounded-pill bg-success mt-2 px-3">Nhân viên</span>
        </div>
        
        <nav class="sidebar-nav flex-grow-1 px-3 overflow-y-auto">
            <!-- TỔNG QUAN -->
            <div class="nav-section mb-4">
                <span class="nav-section-title px-3">TỔNG QUAN</span>
                <ul class="nav flex-column mt-2">
                    <li class="nav-item">
                        <a href="<?= e(staff_url('dashboard')) ?>" class="nav-link <?= in_array($currentPage, ['dashboard', 'staff_dashboard']) ? 'active' : '' ?>">
                            <i class="fa-solid fa-house nav-icon"></i> Tổng quan
                        </a>
                    </li>
                </ul>
            </div>

            <!-- CÁ NHÂN -->
            <div class="nav-section mb-4">
                <span class="nav-section-title px-3">CÁ NHÂN</span>
                <ul class="nav flex-column mt-2">
                    <li class="nav-item">
                        <a href="<?= e(staff_url('my-shifts')) ?>" class="nav-link <?= $currentPage === 'my-shifts' ? 'active' : '' ?>">
                            <i class="fa-solid fa-calendar-check nav-icon"></i> Lịch làm việc
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= e(staff_url('profile')) ?>" class="nav-link <?= in_array($currentPage, ['profile', 'account']) ? 'active' : '' ?>">
                            <i class="fa-solid fa-user-shield nav-icon"></i> Tài khoản
                        </a>
                    </li>
                </ul>
            </div>

            <!-- BÁN HÀNG -->
            <div class="nav-section mb-4">
                <span class="nav-section-title px-3">BÁN HÀNG</span>
                <ul class="nav flex-column mt-2">
                    <li class="nav-item">
                        <a href="<?= e(staff_url('orders')) ?>" class="nav-link <?= in_array($currentPage, ['orders', 'order-detail']) ? 'active' : '' ?>">
                            <i class="fa-solid fa-receipt nav-icon"></i> Đơn hàng
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= e(staff_url('order-create')) ?>" class="nav-link <?= $currentPage === 'order-create' ? 'active' : '' ?>">
                            <i class="fa-solid fa-cart-plus nav-icon"></i> Gọi món (POS)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= e(staff_url('tables')) ?>" class="nav-link <?= $currentPage === 'tables' ? 'active' : '' ?>">
                            <i class="fa-solid fa-chair nav-icon"></i> Bàn
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= e(staff_url('shift_sessions')) ?>" class="nav-link <?= $currentPage === 'shift_sessions' ? 'active' : '' ?>">
                            <i class="fa-solid fa-cash-register nav-icon"></i> Ca làm & Bàn giao
                        </a>
                    </li>
                </ul>
            </div>

            <!-- KHO -->
            <div class="nav-section mb-4">
                <span class="nav-section-title px-3">KHO</span>
                <ul class="nav flex-column mt-2">
                    <li class="nav-item">
                        <a href="<?= e(staff_url('inventory')) ?>" class="nav-link <?= $currentPage === 'inventory' ? 'active' : '' ?>">
                            <i class="fa-solid fa-boxes-stacked nav-icon"></i> Nguyên liệu
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= e(staff_url('inventory_export')) ?>" class="nav-link <?= $currentPage === 'inventory_export' ? 'active' : '' ?>">
                            <i class="fa-solid fa-truck-fast nav-icon"></i> Xuất kho
                        </a>
                    </li>
                </ul>
            </div>

            <!-- HỆ THỐNG -->
            <div class="nav-section mb-4">
                <span class="nav-section-title px-3">HỆ THỐNG</span>
                <ul class="nav flex-column mt-2">
                    <li class="nav-item">
                        <a href="<?= e(url('home')) ?>" target="_blank" class="nav-link text-success">
                            <i class="fa-solid fa-arrow-up-right-from-square nav-icon"></i> Xem Website
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        
        <div class="sidebar-footer p-3 mt-auto border-top">
            <a href="<?= e(staff_url('logout')) ?>" class="btn btn-outline-danger w-100 rounded-3">
                <i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Đăng xuất
            </a>
        </div>
    </aside>

    <!-- Mobile Sidebar (Offcanvas) -->
    <div class="offcanvas offcanvas-start admin-sidebar-mobile" tabindex="-1" id="staffSidebar" aria-labelledby="staffSidebarLabel">
        <div class="offcanvas-header border-bottom">
            <div>
                <h5 class="font-serif fw-bold text-forest mb-0" id="staffSidebarLabel">VAA THÉ</h5>
                <span class="small text-muted">Khu vực nhân viên</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Đóng"></button>
        </div>
        <div class="offcanvas-body p-3">
            <nav class="sidebar-nav">
                <!-- TỔNG QUAN -->
                <div class="nav-section mb-4">
                    <span class="nav-section-title px-3">TỔNG QUAN</span>
                    <ul class="nav flex-column mt-2">
                        <li class="nav-item">
                            <a href="<?= e(staff_url('dashboard')) ?>" class="nav-link <?= in_array($currentPage, ['dashboard', 'staff_dashboard']) ? 'active' : '' ?>">
                                <i class="fa-solid fa-house nav-icon"></i> Tổng quan
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- CÁ NHÂN -->
                <div class="nav-section mb-4">
                    <span class="nav-section-title px-3">CÁ NHÂN</span>
                    <ul class="nav flex-column mt-2">
                        <li class="nav-item">
                            <a href="<?= e(staff_url('my-shifts')) ?>" class="nav-link <?= $currentPage === 'my-shifts' ? 'active' : '' ?>">
                                <i class="fa-solid fa-calendar-check nav-icon"></i> Lịch làm việc
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= e(staff_url('profile')) ?>" class="nav-link <?= in_array($currentPage, ['profile', 'account']) ? 'active' : '' ?>">
                                <i class="fa-solid fa-user-shield nav-icon"></i> Tài khoản
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- BÁN HÀNG -->
                <div class="nav-section mb-4">
                    <span class="nav-section-title px-3">BÁN HÀNG</span>
                    <ul class="nav flex-column mt-2">
                        <li class="nav-item">
                            <a href="<?= e(staff_url('orders')) ?>" class="nav-link <?= in_array($currentPage, ['orders', 'order-detail']) ? 'active' : '' ?>">
                                <i class="fa-solid fa-receipt nav-icon"></i> Đơn hàng
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= e(staff_url('order-create')) ?>" class="nav-link <?= $currentPage === 'order-create' ? 'active' : '' ?>">
                                <i class="fa-solid fa-cart-plus nav-icon"></i> Gọi món (POS)
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= e(staff_url('tables')) ?>" class="nav-link <?= $currentPage === 'tables' ? 'active' : '' ?>">
                                <i class="fa-solid fa-chair nav-icon"></i> Bàn
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= e(staff_url('shift_sessions')) ?>" class="nav-link <?= $currentPage === 'shift_sessions' ? 'active' : '' ?>">
                                <i class="fa-solid fa-cash-register nav-icon"></i> Ca làm & Bàn giao
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- KHO -->
                <div class="nav-section mb-4">
                    <span class="nav-section-title px-3">KHO</span>
                    <ul class="nav flex-column mt-2">
                        <li class="nav-item">
                            <a href="<?= e(staff_url('inventory')) ?>" class="nav-link <?= $currentPage === 'inventory' ? 'active' : '' ?>">
                                <i class="fa-solid fa-boxes-stacked nav-icon"></i> Nguyên liệu
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= e(staff_url('inventory_export')) ?>" class="nav-link <?= $currentPage === 'inventory_export' ? 'active' : '' ?>">
                                <i class="fa-solid fa-truck-fast nav-icon"></i> Xuất kho
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- HỆ THỐNG -->
                <div class="nav-section mb-4">
                    <span class="nav-section-title px-3">HỆ THỐNG</span>
                    <ul class="nav flex-column mt-2">
                        <li class="nav-item">
                            <a href="<?= e(url('home')) ?>" target="_blank" class="nav-link text-success">
                                <i class="fa-solid fa-arrow-up-right-from-square nav-icon"></i> Xem Website
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
            <div class="sidebar-footer p-3 mt-auto border-top">
                <a href="<?= e(staff_url('logout')) ?>" class="btn btn-outline-danger w-100 rounded-3">
                    <i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Đăng xuất
                </a>
            </div>
        </div>
    </div>

    <!-- Main Wrapper -->
    <div class="admin-main-wrapper">
        <!-- Topbar -->
        <header class="admin-topbar d-flex align-items-center justify-content-between px-4">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-light d-lg-none btn-menu-toggle" type="button" data-bs-toggle="offcanvas" data-bs-target="#staffSidebar" aria-label="Mở menu">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <nav aria-label="breadcrumb" class="d-none d-sm-block">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item text-muted">Nhân viên</li>
                        <li class="breadcrumb-item active fw-bold text-forest" aria-current="page"><?= e($pageTitle) ?></li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="badge rounded-pill bg-success px-3 py-2">Nhân viên</span>
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle rounded-pill px-3 py-2" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-regular fa-user me-2"></i><?= e($staffName) ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                        <li><a class="dropdown-item" href="<?= e(staff_url('profile')) ?>"><i class="fa-regular fa-user me-2"></i> Tài khoản của tôi</a></li>
                        <li><a class="dropdown-item" href="<?= e(staff_url('my-shifts')) ?>"><i class="fa-solid fa-calendar-check me-2"></i> Lịch làm việc</a></li>
                        <li><a class="dropdown-item" href="<?= e(url('home')) ?>" target="_blank"><i class="fa-solid fa-globe me-2"></i> Xem Website</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="<?= e(staff_url('logout')) ?>"><i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Đăng xuất</a></li>
                    </ul>
                </div>
            </div>
        </header>
        <main class="admin-content p-4">
