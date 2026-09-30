<?php
$currentRoute = $_GET['route'] ?? 'dashboard';
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
$isStaff = isset($_SESSION['role']) && $_SESSION['role'] === 'staff';
?>

<!-- Desktop Sidebar -->
<aside class="admin-sidebar d-none d-lg-flex flex-column">
    <div class="sidebar-brand px-4 py-4 text-center">
        <a href="index.php" style="text-decoration: none;"><h2 class="font-serif fw-bold text-forest mb-1">VAA THÉ</h2></a>
        <p class="small text-muted mb-0 fst-italic">GOOD TEA, GOOD MOOD</p>
    </div>
    
    <nav class="sidebar-nav flex-grow-1 px-3 overflow-y-auto">
        <div class="nav-section mb-4">
            <span class="nav-section-title px-3">TỔNG QUAN</span>
            <ul class="nav flex-column mt-2">
                <?php if ($isAdmin): ?>
                <li class="nav-item">
                    <a href="admin.php?route=dashboard" class="nav-link <?= $currentRoute === 'dashboard' ? 'active' : '' ?>">
                        <i class="fa-solid fa-chart-line nav-icon"></i> Tổng quan
                    </a>
                </li>
                <?php endif; ?>
                <?php if ($isStaff): ?>
                <li class="nav-item">
                    <a href="admin.php?route=staff_dashboard" class="nav-link <?= $currentRoute === 'staff_dashboard' ? 'active' : '' ?>">
                        <i class="fa-solid fa-house nav-icon"></i> Tổng quan
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>

        <?php if ($isStaff): ?>
        <div class="nav-section mb-4">
            <span class="nav-section-title px-3">CÁ NHÂN</span>
            <ul class="nav flex-column mt-2">
                <li class="nav-item">
                    <a href="admin.php?route=my-shifts" class="nav-link <?= $currentRoute === 'my-shifts' ? 'active' : '' ?>">
                        <i class="fa-solid fa-calendar-check nav-icon"></i> Lịch làm việc
                    </a>
                </li>
                <li class="nav-item">
                    <a href="admin.php?route=account" class="nav-link <?= $currentRoute === 'account' ? 'active' : '' ?>">
                        <i class="fa-solid fa-user-shield nav-icon"></i> Tài khoản
                    </a>
                </li>
            </ul>
        </div>
        <?php endif; ?>

        <div class="nav-section mb-4">
            <span class="nav-section-title px-3">BÁN HÀNG</span>
            <ul class="nav flex-column mt-2">
                <li class="nav-item">
                    <a href="admin.php?route=orders" class="nav-link <?= $currentRoute === 'orders' ? 'active' : '' ?>">
                        <i class="fa-solid fa-receipt nav-icon"></i> Đơn hàng
                    </a>
                </li>
                <li class="nav-item">
                    <a href="admin.php?route=tables" class="nav-link <?= $currentRoute === 'tables' ? 'active' : '' ?>">
                        <i class="fa-solid fa-chair nav-icon"></i> Bàn
                    </a>
                </li>
                <?php if ($isAdmin): ?>
                <li class="nav-item">
                    <a href="admin.php?route=customers" class="nav-link <?= $currentRoute === 'customers' ? 'active' : '' ?>">
                        <i class="fa-solid fa-users nav-icon"></i> Khách hàng
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>

        <?php if ($isAdmin): ?>
        <div class="nav-section mb-4">
            <span class="nav-section-title px-3">MENU</span>
            <ul class="nav flex-column mt-2">
                <li class="nav-item">
                    <a href="admin.php?route=products" class="nav-link <?= $currentRoute === 'products' ? 'active' : '' ?>">
                        <i class="fa-solid fa-cup-togo nav-icon"></i> Sản phẩm
                    </a>
                </li>
                <li class="nav-item">
                    <a href="admin.php?route=categories" class="nav-link <?= $currentRoute === 'categories' ? 'active' : '' ?>">
                        <i class="fa-regular fa-folder nav-icon"></i> Danh mục
                    </a>
                </li>
                <li class="nav-item">
                    <a href="admin.php?route=options" class="nav-link <?= $currentRoute === 'options' ? 'active' : '' ?>">
                        <i class="fa-solid fa-sliders nav-icon"></i> Item Options
                    </a>
                </li>
                <li class="nav-item">
                    <a href="admin.php?route=toppings" class="nav-link <?= $currentRoute === 'toppings' || $currentRoute === 'sizes' ? 'active' : '' ?>">
                        <i class="fa-solid fa-cube nav-icon"></i> Topping/Size
                    </a>
                </li>
            </ul>
        </div>
        <?php endif; ?>

        <div class="nav-section mb-4">
            <span class="nav-section-title px-3">KHO</span>
            <ul class="nav flex-column mt-2">
                <li class="nav-item">
                    <a href="admin.php?route=inventory" class="nav-link <?= $currentRoute === 'inventory' ? 'active' : '' ?>">
                        <i class="fa-solid fa-boxes-stacked nav-icon"></i> Nguyên liệu
                    </a>
                </li>
                <li class="nav-item">
                    <a href="admin.php?route=inventory_import" class="nav-link <?= $currentRoute === 'inventory_import' ? 'active' : '' ?>">
                        <i class="fa-solid fa-box-open nav-icon"></i> Nhập kho
                    </a>
                </li>
                <li class="nav-item">
                    <a href="admin.php?route=inventory_export" class="nav-link <?= $currentRoute === 'inventory_export' ? 'active' : '' ?>">
                        <i class="fa-solid fa-truck-fast nav-icon"></i> Xuất kho
                    </a>
                </li>
                <?php if ($isAdmin): ?>
                <li class="nav-item">
                    <a href="admin.php?route=product_ingredients" class="nav-link <?= $currentRoute === 'product_ingredients' ? 'active' : '' ?>">
                        <i class="fa-solid fa-blender nav-icon"></i> Công thức sản phẩm
                    </a>
                </li>
                <li class="nav-item">
                    <a href="admin.php?route=inventory_alerts" class="nav-link <?= $currentRoute === 'inventory_alerts' ? 'active' : '' ?>">
                        <i class="fa-solid fa-triangle-exclamation nav-icon"></i> Cảnh báo tồn kho
                    </a>
                </li>
                <li class="nav-item">
                    <a href="admin.php?route=inventory_history" class="nav-link <?= $currentRoute === 'inventory_history' ? 'active' : '' ?>">
                        <i class="fa-solid fa-clock-rotate-left nav-icon"></i> Lịch sử kho
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>

        <?php if ($isAdmin): ?>
        <div class="nav-section mb-4">
            <span class="nav-section-title px-3">NHÂN SỰ</span>
            <ul class="nav flex-column mt-2">
                <li class="nav-item">
                    <a href="admin.php?route=staff" class="nav-link <?= $currentRoute === 'staff' ? 'active' : '' ?>">
                        <i class="fa-solid fa-user-tie nav-icon"></i> Nhân viên
                    </a>
                </li>
                <li class="nav-item">
                    <a href="admin.php?route=shifts" class="nav-link <?= $currentRoute === 'shifts' ? 'active' : '' ?>">
                        <i class="fa-solid fa-calendar-days nav-icon"></i> Ca làm việc
                    </a>
                </li>
            </ul>
        </div>

        <div class="nav-section mb-4">
            <span class="nav-section-title px-3">KINH DOANH</span>
            <ul class="nav flex-column mt-2">
                <!-- <li class="nav-item">
                    <a href="#" class="nav-link <?= $currentRoute === 'promotions' ? 'active' : '' ?>">
                        <i class="fa-solid fa-tags nav-icon"></i> Khuyến mãi
                    </a>
                </li> -->
                <li class="nav-item">
                    <a href="#" class="nav-link <?= $currentRoute === 'payments' ? 'active' : '' ?>">
                        <i class="fa-solid fa-credit-card nav-icon"></i> Thanh toán
                    </a>
                </li>
                <li class="nav-item">
                    <a href="admin.php?route=reports" class="nav-link <?= $currentRoute === 'reports' ? 'active' : '' ?>">
                        <i class="fa-solid fa-chart-pie nav-icon"></i> Báo cáo
                    </a>
                </li>
                <li class="nav-item">
                    <a href="admin.php?route=inventory_report" class="nav-link <?= $currentRoute === 'inventory_report' ? 'active' : '' ?>">
                        <i class="fa-solid fa-boxes-stacked nav-icon"></i> Báo cáo tồn kho
                    </a>
                </li>
            </ul>
        </div>

        <div class="nav-section mb-4">
            <span class="nav-section-title px-3">HỆ THỐNG</span>
            <ul class="nav flex-column mt-2">
                <li class="nav-item">
                    <a href="admin.php?route=settings" class="nav-link <?= $currentRoute === 'settings' ? 'active' : '' ?>">
                        <i class="fa-solid fa-gear nav-icon"></i> Cài đặt
                    </a>
                </li>
                <li class="nav-item">
                    <a href="admin.php?route=account" class="nav-link <?= $currentRoute === 'account' ? 'active' : '' ?>">
                        <i class="fa-solid fa-user-shield nav-icon"></i> Tài khoản
                    </a>
                </li>
            </ul>
        </div>
        <?php endif; ?>
    </nav>
    
    <div class="sidebar-footer p-3 mt-auto border-top">
        <a href="index.php?route=logout" class="btn btn-outline-danger w-100 rounded-3">
            <i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Đăng xuất
        </a>
    </div>
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
        <!-- Re-use the same nav HTML structure -->
        <nav class="sidebar-nav flex-grow-1 p-3 overflow-y-auto">
            <!-- (Mobile content mirrors desktop, simplified here for brevity, assuming identical logic) -->
            <div class="nav-section mb-4">
                <span class="nav-section-title px-3">TỔNG QUAN</span>
                <ul class="nav flex-column mt-2">
                    <?php if ($isAdmin): ?>
                    <li class="nav-item"><a href="admin.php?route=dashboard" class="nav-link"><i class="fa-solid fa-chart-line nav-icon"></i> Tổng quan</a></li>
                    <?php endif; ?>
                    <?php if ($isStaff): ?>
                    <li class="nav-item"><a href="admin.php?route=staff_dashboard" class="nav-link"><i class="fa-solid fa-house nav-icon"></i> Tổng quan</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            
            <?php if ($isStaff): ?>
            <div class="nav-section mb-4">
                <span class="nav-section-title px-3">CÁ NHÂN</span>
                <ul class="nav flex-column mt-2">
                    <li class="nav-item"><a href="admin.php?route=my-shifts" class="nav-link"><i class="fa-solid fa-calendar-check nav-icon"></i> Lịch làm việc</a></li>
                    <li class="nav-item"><a href="admin.php?route=account" class="nav-link"><i class="fa-solid fa-user-shield nav-icon"></i> Tài khoản</a></li>
                </ul>
            </div>
            <?php endif; ?>

            <div class="nav-section mb-4">
                <span class="nav-section-title px-3">BÁN HÀNG</span>
                <ul class="nav flex-column mt-2">
                    <li class="nav-item"><a href="admin.php?route=orders" class="nav-link"><i class="fa-solid fa-receipt nav-icon"></i> Đơn hàng</a></li>
                    <li class="nav-item"><a href="admin.php?route=tables" class="nav-link"><i class="fa-solid fa-chair nav-icon"></i> Bàn</a></li>
                    <?php if ($isAdmin): ?>
                    <li class="nav-item"><a href="admin.php?route=customers" class="nav-link"><i class="fa-solid fa-users nav-icon"></i> Khách hàng</a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <?php if ($isAdmin): ?>
            <div class="nav-section mb-4">
                <span class="nav-section-title px-3">MENU</span>
                <ul class="nav flex-column mt-2">
                    <li class="nav-item"><a href="admin.php?route=products" class="nav-link"><i class="fa-solid fa-cup-togo nav-icon"></i> Sản phẩm</a></li>
                    <li class="nav-item"><a href="admin.php?route=categories" class="nav-link"><i class="fa-regular fa-folder nav-icon"></i> Danh mục</a></li>
                    <li class="nav-item"><a href="admin.php?route=options" class="nav-link"><i class="fa-solid fa-sliders nav-icon"></i> Item Options</a></li>
                    <li class="nav-item"><a href="admin.php?route=toppings" class="nav-link"><i class="fa-solid fa-cube nav-icon"></i> Topping/Size</a></li>
                </ul>
            </div>
            <?php endif; ?>

            <div class="nav-section mb-4">
                <span class="nav-section-title px-3">KHO</span>
                <ul class="nav flex-column mt-2">
                    <li class="nav-item"><a href="admin.php?route=inventory" class="nav-link"><i class="fa-solid fa-boxes-stacked nav-icon"></i> Nguyên liệu</a></li>
                    <li class="nav-item"><a href="admin.php?route=inventory_import" class="nav-link"><i class="fa-solid fa-box-open nav-icon"></i> Nhập kho</a></li>
                    <li class="nav-item"><a href="admin.php?route=inventory_export" class="nav-link"><i class="fa-solid fa-truck-fast nav-icon"></i> Xuất kho</a></li>
                    <?php if ($isAdmin): ?>
                    <li class="nav-item"><a href="admin.php?route=product_ingredients" class="nav-link"><i class="fa-solid fa-blender nav-icon"></i> Công thức sản phẩm</a></li>
                    <li class="nav-item"><a href="admin.php?route=inventory_alerts" class="nav-link"><i class="fa-solid fa-triangle-exclamation nav-icon"></i> Cảnh báo tồn kho</a></li>
                    <li class="nav-item"><a href="admin.php?route=inventory_history" class="nav-link"><i class="fa-solid fa-clock-rotate-left nav-icon"></i> Lịch sử kho</a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <?php if ($isAdmin): ?>
            <div class="nav-section mb-4">
                <span class="nav-section-title px-3">NHÂN SỰ</span>
                <ul class="nav flex-column mt-2">
                    <li class="nav-item"><a href="admin.php?route=staff" class="nav-link"><i class="fa-solid fa-user-tie nav-icon"></i> Nhân viên</a></li>
                    <li class="nav-item"><a href="admin.php?route=shifts" class="nav-link"><i class="fa-solid fa-calendar-days nav-icon"></i> Ca làm việc</a></li>
                </ul>
            </div>

            <div class="nav-section mb-4">
                <span class="nav-section-title px-3">KINH DOANH</span>
                <ul class="nav flex-column mt-2">
                    <li class="nav-item"><a href="#" class="nav-link"><i class="fa-solid fa-tags nav-icon"></i> Khuyến mãi</a></li>
                    <li class="nav-item"><a href="#" class="nav-link"><i class="fa-solid fa-credit-card nav-icon"></i> Thanh toán</a></li>
                    <li class="nav-item"><a href="admin.php?route=reports" class="nav-link"><i class="fa-solid fa-chart-pie nav-icon"></i> Báo cáo</a></li>
                    <li class="nav-item"><a href="admin.php?route=inventory_report" class="nav-link"><i class="fa-solid fa-boxes-stacked nav-icon"></i> Báo cáo tồn kho</a></li>
                </ul>
            </div>

            <div class="nav-section mb-4">
                <span class="nav-section-title px-3">HỆ THỐNG</span>
                <ul class="nav flex-column mt-2">
                    <li class="nav-item"><a href="admin.php?route=settings" class="nav-link"><i class="fa-solid fa-gear nav-icon"></i> Cài đặt</a></li>
                    <li class="nav-item"><a href="admin.php?route=account" class="nav-link"><i class="fa-solid fa-user-shield nav-icon"></i> Tài khoản</a></li>
                </ul>
            </div>
            <?php endif; ?>
        </nav>
    </div>
</div>
