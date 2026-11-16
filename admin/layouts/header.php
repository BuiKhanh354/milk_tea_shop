<?php
// admin/layouts/header.php — Layout Admin chuẩn gốc VAA THÉ với phân quyền Staff & Admin
$pageTitle = $title ?? 'Admin Dashboard';
$currentPage = get_param('page', 'dashboard');
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
$isStaff = isset($_SESSION['role']) && $_SESSION['role'] === 'staff';
$adminName = $_SESSION['full_name'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> - VAA THÉ</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS Admin chuẩn gốc -->
    <link rel="stylesheet" href="<?= e(asset('assets/css/style.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('assets/css/admin.css')) ?>">
</head>
<body class="admin-body">

    <!-- Desktop Sidebar -->
    <aside class="admin-sidebar d-none d-lg-flex flex-column">
        <div class="sidebar-brand px-4 py-4 text-center">
            <a href="<?= e(url('home')) ?>" class="text-decoration-none">
                <h2 class="font-serif fw-bold text-forest mb-1">VAA THÉ</h2>
            </a>
            <p class="small text-muted mb-0 fst-italic">GOOD TEA, GOOD MOOD</p>
        </div>
        
        <nav class="sidebar-nav flex-grow-1 px-3 overflow-y-auto">
            <!-- TỔNG QUAN -->
            <div class="nav-section mb-4">
                <span class="nav-section-title px-3">TỔNG QUAN</span>
                <ul class="nav flex-column mt-2">
                    <?php if ($isAdmin): ?>
                    <li class="nav-item">
                        <a href="<?= e(admin_url('dashboard')) ?>" class="nav-link <?= $currentPage === 'dashboard' ? 'active' : '' ?>">
                            <i class="fa-solid fa-chart-line nav-icon"></i> Tổng quan
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if ($isStaff): ?>
                    <li class="nav-item">
                        <a href="<?= e(admin_url('dashboard')) ?>" class="nav-link <?= in_array($currentPage, ['dashboard', 'staff_dashboard']) ? 'active' : '' ?>">
                            <i class="fa-solid fa-house nav-icon"></i> Tổng quan
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- CÁ NHÂN (Dành riêng cho Nhân viên) -->
            <?php if ($isStaff): ?>
            <div class="nav-section mb-4">
                <span class="nav-section-title px-3">CÁ NHÂN</span>
                <ul class="nav flex-column mt-2">
                    <li class="nav-item">
                        <a href="<?= e(admin_url('my-shifts')) ?>" class="nav-link <?= $currentPage === 'my-shifts' ? 'active' : '' ?>">
                            <i class="fa-solid fa-calendar-check nav-icon"></i> Lịch làm việc
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= e(admin_url('profile')) ?>" class="nav-link <?= in_array($currentPage, ['profile', 'account']) ? 'active' : '' ?>">
                            <i class="fa-solid fa-user-shield nav-icon"></i> Tài khoản
                        </a>
                    </li>
                </ul>
            </div>
            <?php endif; ?>

            <!-- BÁN HÀNG -->
            <div class="nav-section mb-4">
                <span class="nav-section-title px-3">BÁN HÀNG</span>
                <ul class="nav flex-column mt-2">
                    <li class="nav-item">
                        <a href="<?= e(admin_url('orders')) ?>" class="nav-link <?= in_array($currentPage, ['orders', 'order-detail']) ? 'active' : '' ?>">
                            <i class="fa-solid fa-receipt nav-icon"></i> Đơn hàng
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= e(admin_url('order-create')) ?>" class="nav-link <?= $currentPage === 'order-create' ? 'active' : '' ?>">
                            <i class="fa-solid fa-cart-plus nav-icon"></i> Gọi món (POS)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= e(admin_url('tables')) ?>" class="nav-link <?= $currentPage === 'tables' ? 'active' : '' ?>">
                            <i class="fa-solid fa-chair nav-icon"></i> Bàn
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= e(admin_url('shift_sessions')) ?>" class="nav-link <?= $currentPage === 'shift_sessions' ? 'active' : '' ?>">
                            <i class="fa-solid fa-cash-register nav-icon"></i> Ca làm & Bàn giao
                        </a>
                    </li>
                    <?php if ($isAdmin): ?>
                    <li class="nav-item">
                        <a href="<?= e(admin_url('customers')) ?>" class="nav-link <?= $currentPage === 'customers' ? 'active' : '' ?>">
                            <i class="fa-solid fa-users nav-icon"></i> Khách hàng
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>

            <?php if ($isAdmin): ?>
            <!-- MENU (Chỉ Admin) -->
            <div class="nav-section mb-4">
                <span class="nav-section-title px-3">MENU</span>
                <ul class="nav flex-column mt-2">
                    <li class="nav-item">
                        <a href="<?= e(admin_url('products')) ?>" class="nav-link <?= in_array($currentPage, ['products', 'product-create', 'product-edit']) ? 'active' : '' ?>">
                            <i class="fa-solid fa-mug-hot nav-icon"></i> Sản phẩm
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= e(admin_url('categories')) ?>" class="nav-link <?= $currentPage === 'categories' ? 'active' : '' ?>">
                            <i class="fa-regular fa-folder nav-icon"></i> Danh mục
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= e(admin_url('options')) ?>" class="nav-link <?= $currentPage === 'options' ? 'active' : '' ?>">
                            <i class="fa-solid fa-sliders nav-icon"></i> Item Options
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= e(admin_url('toppings')) ?>" class="nav-link <?= $currentPage === 'toppings' ? 'active' : '' ?>">
                            <i class="fa-solid fa-cubes nav-icon"></i> Topping/Size
                        </a>
                    </li>
                </ul>
            </div>
            <?php endif; ?>

            <!-- KHO -->
            <div class="nav-section mb-4">
                <span class="nav-section-title px-3">KHO</span>
                <ul class="nav flex-column mt-2">
                    <li class="nav-item">
                        <a href="<?= e(admin_url('inventory')) ?>" class="nav-link <?= $currentPage === 'inventory' ? 'active' : '' ?>">
                            <i class="fa-solid fa-boxes-stacked nav-icon"></i> Nguyên liệu
                        </a>
                    </li>
                    <?php if ($isAdmin): ?>
                    <li class="nav-item">
                        <a href="<?= e(admin_url('inventory_import')) ?>" class="nav-link <?= $currentPage === 'inventory_import' ? 'active' : '' ?>">
                            <i class="fa-solid fa-box-open nav-icon"></i> Nhập kho
                        </a>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a href="<?= e(admin_url('inventory_export')) ?>" class="nav-link <?= $currentPage === 'inventory_export' ? 'active' : '' ?>">
                            <i class="fa-solid fa-truck-fast nav-icon"></i> Xuất kho
                        </a>
                    </li>
                    <?php if ($isAdmin): ?>
                    <li class="nav-item">
                        <a href="<?= e(admin_url('product_ingredients')) ?>" class="nav-link <?= $currentPage === 'product_ingredients' ? 'active' : '' ?>">
                            <i class="fa-solid fa-blender nav-icon"></i> Công thức sản phẩm
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= e(admin_url('inventory_alerts')) ?>" class="nav-link <?= $currentPage === 'inventory_alerts' ? 'active' : '' ?>">
                            <i class="fa-solid fa-triangle-exclamation nav-icon"></i> Cảnh báo tồn kho
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>

            <?php if ($isAdmin): ?>
            <!-- NHÂN SỰ (Chỉ Admin) -->
            <div class="nav-section mb-4">
                <span class="nav-section-title px-3">NHÂN SỰ</span>
                <ul class="nav flex-column mt-2">
                    <li class="nav-item">
                        <a href="<?= e(admin_url('staff')) ?>" class="nav-link <?= $currentPage === 'staff' ? 'active' : '' ?>">
                            <i class="fa-solid fa-user-tie nav-icon"></i> Nhân viên
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= e(admin_url('shifts')) ?>" class="nav-link <?= $currentPage === 'shifts' ? 'active' : '' ?>">
                            <i class="fa-solid fa-calendar-days nav-icon"></i> Ca làm việc
                        </a>
                    </li>
                </ul>
            </div>

            <!-- KINH DOANH (Chỉ Admin) -->
            <div class="nav-section mb-4">
                <span class="nav-section-title px-3">KINH DOANH</span>
                <ul class="nav flex-column mt-2">
                    <li class="nav-item">
                        <a href="<?= e(admin_url('payments')) ?>" class="nav-link <?= $currentPage === 'payments' ? 'active' : '' ?>">
                            <i class="fa-solid fa-credit-card nav-icon"></i> Thanh toán
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= e(admin_url('reports')) ?>" class="nav-link <?= $currentPage === 'reports' ? 'active' : '' ?>">
                            <i class="fa-solid fa-chart-pie nav-icon"></i> Báo cáo
                        </a>
                    </li>
                </ul>
            </div>
            <?php endif; ?>

            <!-- HỆ THỐNG -->
            <div class="nav-section mb-4">
                <span class="nav-section-title px-3">HỆ THỐNG</span>
                <ul class="nav flex-column mt-2">
                    <?php if ($isAdmin): ?>
                    <li class="nav-item">
                        <a href="<?= e(admin_url('settings')) ?>" class="nav-link <?= $currentPage === 'settings' ? 'active' : '' ?>">
                            <i class="fa-solid fa-gear nav-icon"></i> Cài đặt
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= e(admin_url('profile')) ?>" class="nav-link <?= in_array($currentPage, ['profile', 'account']) ? 'active' : '' ?>">
                            <i class="fa-regular fa-user nav-icon"></i> Hồ sơ
                        </a>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a href="<?= e(url('home')) ?>" target="_blank" class="nav-link text-success">
                            <i class="fa-solid fa-arrow-up-right-from-square nav-icon"></i> Xem Website
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= e(admin_url('logout')) ?>" class="nav-link text-danger">
                            <i class="fa-solid fa-arrow-right-from-bracket nav-icon"></i> Đăng xuất
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </aside>

    <!-- Mobile Offcanvas Sidebar -->
    <div class="offcanvas offcanvas-start admin-sidebar-mobile" tabindex="-1" id="mobileSidebar">
        <div class="offcanvas-header border-bottom">
            <div class="sidebar-brand">
                <h4 class="font-serif fw-bold text-forest mb-0">VAA THÉ</h4>
            </div>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0 d-flex flex-column">
            <nav class="sidebar-nav flex-grow-1 p-3 overflow-y-auto">
                <div class="nav-section mb-4">
                    <span class="nav-section-title px-3">TỔNG QUAN</span>
                    <ul class="nav flex-column mt-2">
                        <?php if ($isAdmin): ?>
                        <li class="nav-item"><a href="<?= e(admin_url('dashboard')) ?>" class="nav-link <?= $currentPage === 'dashboard' ? 'active' : '' ?>"><i class="fa-solid fa-chart-line nav-icon"></i> Tổng quan</a></li>
                        <?php endif; ?>
                        <?php if ($isStaff): ?>
                        <li class="nav-item"><a href="<?= e(admin_url('dashboard')) ?>" class="nav-link <?= in_array($currentPage, ['dashboard', 'staff_dashboard']) ? 'active' : '' ?>"><i class="fa-solid fa-house nav-icon"></i> Tổng quan</a></li>
                        <?php endif; ?>
                    </ul>
                </div>

                <?php if ($isStaff): ?>
                <div class="nav-section mb-4">
                    <span class="nav-section-title px-3">CÁ NHÂN</span>
                    <ul class="nav flex-column mt-2">
                        <li class="nav-item"><a href="<?= e(admin_url('my-shifts')) ?>" class="nav-link <?= $currentPage === 'my-shifts' ? 'active' : '' ?>"><i class="fa-solid fa-calendar-check nav-icon"></i> Lịch làm việc</a></li>
                        <li class="nav-item"><a href="<?= e(admin_url('profile')) ?>" class="nav-link <?= in_array($currentPage, ['profile', 'account']) ? 'active' : '' ?>"><i class="fa-solid fa-user-shield nav-icon"></i> Tài khoản</a></li>
                    </ul>
                </div>
                <?php endif; ?>

                <div class="nav-section mb-4">
                    <span class="nav-section-title px-3">BÁN HÀNG</span>
                    <ul class="nav flex-column mt-2">
                        <li class="nav-item"><a href="<?= e(admin_url('orders')) ?>" class="nav-link <?= in_array($currentPage, ['orders', 'order-detail']) ? 'active' : '' ?>"><i class="fa-solid fa-receipt nav-icon"></i> Đơn hàng</a></li>
                        <li class="nav-item"><a href="<?= e(admin_url('order-create')) ?>" class="nav-link <?= $currentPage === 'order-create' ? 'active' : '' ?>"><i class="fa-solid fa-cart-plus nav-icon"></i> Gọi món (POS)</a></li>
                        <li class="nav-item"><a href="<?= e(admin_url('tables')) ?>" class="nav-link <?= $currentPage === 'tables' ? 'active' : '' ?>"><i class="fa-solid fa-chair nav-icon"></i> Bàn</a></li>
                        <li class="nav-item"><a href="<?= e(admin_url('shift_sessions')) ?>" class="nav-link <?= $currentPage === 'shift_sessions' ? 'active' : '' ?>"><i class="fa-solid fa-cash-register nav-icon"></i> Ca làm & Bàn giao</a></li>
                        <?php if ($isAdmin): ?>
                        <li class="nav-item"><a href="<?= e(admin_url('customers')) ?>" class="nav-link <?= $currentPage === 'customers' ? 'active' : '' ?>"><i class="fa-solid fa-users nav-icon"></i> Khách hàng</a></li>
                        <?php endif; ?>
                    </ul>
                </div>

                <?php if ($isAdmin): ?>
                <div class="nav-section mb-4">
                    <span class="nav-section-title px-3">MENU</span>
                    <ul class="nav flex-column mt-2">
                        <li class="nav-item"><a href="<?= e(admin_url('products')) ?>" class="nav-link <?= in_array($currentPage, ['products', 'product-create', 'product-edit']) ? 'active' : '' ?>"><i class="fa-solid fa-mug-hot nav-icon"></i> Sản phẩm</a></li>
                        <li class="nav-item"><a href="<?= e(admin_url('categories')) ?>" class="nav-link <?= $currentPage === 'categories' ? 'active' : '' ?>"><i class="fa-regular fa-folder nav-icon"></i> Danh mục</a></li>
                        <li class="nav-item"><a href="<?= e(admin_url('options')) ?>" class="nav-link <?= $currentPage === 'options' ? 'active' : '' ?>"><i class="fa-solid fa-sliders nav-icon"></i> Item Options</a></li>
                        <li class="nav-item"><a href="<?= e(admin_url('toppings')) ?>" class="nav-link <?= $currentPage === 'toppings' ? 'active' : '' ?>"><i class="fa-solid fa-cubes nav-icon"></i> Topping/Size</a></li>
                    </ul>
                </div>
                <?php endif; ?>

                <div class="nav-section mb-4">
                    <span class="nav-section-title px-3">KHO</span>
                    <ul class="nav flex-column mt-2">
                        <li class="nav-item"><a href="<?= e(admin_url('inventory')) ?>" class="nav-link <?= $currentPage === 'inventory' ? 'active' : '' ?>"><i class="fa-solid fa-boxes-stacked nav-icon"></i> Nguyên liệu</a></li>
                        <?php if ($isAdmin): ?>
                        <li class="nav-item"><a href="<?= e(admin_url('inventory_import')) ?>" class="nav-link <?= $currentPage === 'inventory_import' ? 'active' : '' ?>"><i class="fa-solid fa-box-open nav-icon"></i> Nhập kho</a></li>
                        <?php endif; ?>
                        <li class="nav-item"><a href="<?= e(admin_url('inventory_export')) ?>" class="nav-link <?= $currentPage === 'inventory_export' ? 'active' : '' ?>"><i class="fa-solid fa-truck-fast nav-icon"></i> Xuất kho</a></li>
                        <?php if ($isAdmin): ?>
                        <li class="nav-item"><a href="<?= e(admin_url('product_ingredients')) ?>" class="nav-link <?= $currentPage === 'product_ingredients' ? 'active' : '' ?>"><i class="fa-solid fa-blender nav-icon"></i> Công thức sản phẩm</a></li>
                        <li class="nav-item"><a href="<?= e(admin_url('inventory_alerts')) ?>" class="nav-link <?= $currentPage === 'inventory_alerts' ? 'active' : '' ?>"><i class="fa-solid fa-triangle-exclamation nav-icon"></i> Cảnh báo tồn kho</a></li>
                        <?php endif; ?>
                    </ul>
                </div>

                <?php if ($isAdmin): ?>
                <div class="nav-section mb-4">
                    <span class="nav-section-title px-3">NHÂN SỰ</span>
                    <ul class="nav flex-column mt-2">
                        <li class="nav-item"><a href="<?= e(admin_url('staff')) ?>" class="nav-link <?= $currentPage === 'staff' ? 'active' : '' ?>"><i class="fa-solid fa-user-tie nav-icon"></i> Nhân viên</a></li>
                        <li class="nav-item"><a href="<?= e(admin_url('shifts')) ?>" class="nav-link <?= $currentPage === 'shifts' ? 'active' : '' ?>"><i class="fa-solid fa-calendar-days nav-icon"></i> Ca làm việc</a></li>
                    </ul>
                </div>

                <div class="nav-section mb-4">
                    <span class="nav-section-title px-3">KINH DOANH</span>
                    <ul class="nav flex-column mt-2">
                        <li class="nav-item"><a href="<?= e(admin_url('payments')) ?>" class="nav-link <?= $currentPage === 'payments' ? 'active' : '' ?>"><i class="fa-solid fa-credit-card nav-icon"></i> Thanh toán</a></li>
                        <li class="nav-item"><a href="<?= e(admin_url('reports')) ?>" class="nav-link <?= $currentPage === 'reports' ? 'active' : '' ?>"><i class="fa-solid fa-chart-pie nav-icon"></i> Báo cáo</a></li>
                    </ul>
                </div>
                <?php endif; ?>

                <div class="nav-section mb-4">
                    <span class="nav-section-title px-3">HỆ THỐNG</span>
                    <ul class="nav flex-column mt-2">
                        <?php if ($isAdmin): ?>
                        <li class="nav-item"><a href="<?= e(admin_url('settings')) ?>" class="nav-link <?= $currentPage === 'settings' ? 'active' : '' ?>"><i class="fa-solid fa-gear nav-icon"></i> Cài đặt</a></li>
                        <li class="nav-item"><a href="<?= e(admin_url('profile')) ?>" class="nav-link <?= in_array($currentPage, ['profile', 'account']) ? 'active' : '' ?>"><i class="fa-regular fa-user nav-icon"></i> Hồ sơ</a></li>
                        <?php endif; ?>
                        <li class="nav-item"><a href="<?= e(url('home')) ?>" target="_blank" class="nav-link text-success"><i class="fa-solid fa-arrow-up-right-from-square nav-icon"></i> Xem Website</a></li>
                    </ul>
                </div>
            </nav>
            <div class="sidebar-footer p-3 mt-auto border-top">
                <a href="<?= e(admin_url('logout')) ?>" class="btn btn-outline-danger w-100 rounded-3">
                    <i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Đăng xuất
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content Wrapper -->
    <div class="admin-main-wrapper">
        
        <!-- Topbar Chuẩn Gốc -->
        <header class="admin-topbar d-flex align-items-center justify-content-between px-4">
            <!-- Left side: Hamburger on mobile + Breadcrumb -->
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-light d-lg-none btn-menu-toggle" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
                    <i class="fa-solid fa-bars"></i>
                </button>
                
                <nav aria-label="breadcrumb" class="d-none d-md-block">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item text-muted">Quản trị</li>
                        <li class="breadcrumb-item active fw-bold text-forest" aria-current="page"><?= e($pageTitle) ?></li>
                    </ol>
                </nav>
            </div>

            <!-- Right side: Search, Shift Session Badge, Notification, User Dropdown -->
            <div class="d-flex align-items-center gap-3">
                <div class="search-box d-none d-md-flex align-items-center bg-light rounded-pill px-3 py-2">
                    <i class="fa-solid fa-magnifying-glass text-muted"></i>
                    <input type="text" class="border-0 bg-transparent ms-2" placeholder="Tìm kiếm..." style="outline: none; font-size: 0.875rem;">
                </div>
                
                <!-- Shift Session Status Badge -->
                <?php
                    $shiftStatus = get_current_user_shift_status();
                    $topbarUserId = $shiftStatus['user_id'];
                    $topbarIsAdmin = $shiftStatus['is_admin'];
                    $topbarOpenSession = $shiftStatus['open_session'];
                    $topbarTodayShift = $shiftStatus['today_shift'];
                ?>
                <?php if (!empty($topbarOpenSession)): ?>
                    <a href="<?= e(admin_url('shift_sessions', ['action' => 'close'])) ?>" class="btn btn-sm btn-success rounded-pill px-3 py-1 shadow-sm d-flex align-items-center gap-2 text-decoration-none" title="Nhấn để kết ca & bàn giao">
                        <span class="spinner-grow spinner-grow-sm text-light" style="width: 8px; height: 8px;"></span>
                        <span class="small fw-bold">Ca: <?= e($topbarOpenSession['shift_name'] ?? $topbarOpenSession['session_code']) ?></span>
                        <span class="badge bg-white text-success rounded-pill small py-1 px-2">Kết ca</span>
                    </a>
                <?php elseif ($topbarTodayShift): ?>
                    <a href="<?= e(admin_url('shift_sessions', ['action' => 'open'])) ?>" class="btn btn-sm btn-outline-forest rounded-pill px-3 py-1 shadow-sm d-flex align-items-center gap-2 text-decoration-none" title="Hôm nay bạn có ca: <?= e($topbarTodayShift['shift_name']) ?>">
                        <i class="fa-solid fa-business-time text-forest"></i>
                        <span class="small fw-bold">Vào ca: <?= e($topbarTodayShift['shift_name']) ?> (<?= substr($topbarTodayShift['start_time'], 0, 5) ?>)</span>
                        <span class="badge bg-forest text-white rounded-pill small py-1 px-2">Mở ca</span>
                    </a>
                <?php elseif ($topbarIsAdmin): ?>
                    <a href="<?= e(admin_url('shift_sessions', ['action' => 'open'])) ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 shadow-sm d-flex align-items-center gap-2 text-decoration-none" title="Mở ca làm việc">
                        <i class="fa-solid fa-lock-open text-muted"></i>
                        <span class="small fw-bold">Mở ca</span>
                    </a>
                <?php else: ?>
                    <span class="badge bg-light text-muted border rounded-pill px-3 py-2 small" title="Không có lịch phân công ca hôm nay">
                        <i class="fa-solid fa-calendar-xmark me-1 text-secondary"></i> Chưa có ca hôm nay
                    </span>
                <?php endif; ?>

                <button class="btn btn-light rounded-circle position-relative btn-notification" type="button" title="Thông báo">
                    <i class="fa-regular fa-bell"></i>
                    <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                        <span class="visually-hidden">New alerts</span>
                    </span>
                </button>
                
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($adminName) ?>&background=263A30&color=fff" alt="User" width="36" height="36" class="rounded-circle me-2 shadow-sm">
                        <div class="d-none d-md-block text-start lh-1">
                            <span class="d-block fw-bold fs-6"><?= e($adminName) ?></span>
                            <span class="text-muted small"><?= ucfirst($_SESSION['role'] ?? 'Administrator') ?></span>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="<?= e(admin_url('profile')) ?>"><i class="fa-regular fa-user me-2"></i> Hồ sơ</a></li>
                        <?php if ($isAdmin): ?>
                        <li><a class="dropdown-item" href="<?= e(admin_url('settings')) ?>"><i class="fa-solid fa-gear me-2"></i> Cài đặt</a></li>
                        <?php else: ?>
                        <li><a class="dropdown-item" href="<?= e(admin_url('my-shifts')) ?>"><i class="fa-solid fa-calendar-check me-2"></i> Lịch làm việc</a></li>
                        <?php endif; ?>
                        <li><a class="dropdown-item" href="<?= e(url('home')) ?>" target="_blank"><i class="fa-solid fa-globe me-2"></i> Xem Website</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="<?= e(admin_url('logout')) ?>"><i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Đăng xuất</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <main class="admin-content p-4">
