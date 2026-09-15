<?php
$currentRoute = $_GET['route'] ?? 'dashboard';
?>

<!-- Desktop Sidebar -->
<aside class="admin-sidebar d-none d-lg-flex flex-column">
    <div class="sidebar-brand px-4 py-4 text-center">
        <a href="index.php" style="text-decoration: none;"><h2 class="font-serif fw-bold text-forest mb-1">VAA THÉ</h2></a>
        <p class="small text-muted mb-0 fst-italic">GOOD TEA, GOOD MOOD</p>
    </div>
    
    <nav class="sidebar-nav flex-grow-1 px-3 overflow-y-auto">
        <div class="nav-section mb-4">
            <span class="nav-section-title px-3">DASHBOARD</span>
            <ul class="nav flex-column mt-2">
                <li class="nav-item">
                    <a href="admin.php?route=dashboard" class="nav-link <?= $currentRoute === 'dashboard' ? 'active' : '' ?>">
                        <i class="fa-solid fa-chart-line nav-icon"></i> Dashboard
                    </a>
                </li>
            </ul>
        </div>

        <div class="nav-section mb-4">
            <span class="nav-section-title px-3">KHO</span>
            <ul class="nav flex-column mt-2">
                <li class="nav-item">
                    <a href="admin.php?route=inventory" class="nav-link <?= $currentRoute === 'inventory' ? 'active' : '' ?>">
                        <i class="fa-solid fa-boxes-stacked nav-icon"></i> Nguyên liệu
                    </a>
                </li>
                <li class="nav-item">
                    <a href="admin.php?route=product_ingredients" class="nav-link <?= $currentRoute === 'product_ingredients' ? 'active' : '' ?>">
                        <i class="fa-solid fa-blender nav-icon"></i> Công thức SP
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
                <li class="nav-item">
                    <a href="admin.php?route=inventory_alerts" class="nav-link <?= $currentRoute === 'inventory_alerts' ? 'active' : '' ?>">
                        <i class="fa-solid fa-triangle-exclamation nav-icon"></i> Cảnh báo
                    </a>
                </li>
                <li class="nav-item">
                    <a href="admin.php?route=inventory_history" class="nav-link <?= $currentRoute === 'inventory_history' ? 'active' : '' ?>">
                        <i class="fa-solid fa-clock-rotate-left nav-icon"></i> Lịch sử kho
                    </a>
                </li>
            </ul>
        </div>

        <div class="nav-section mb-4">
            <span class="nav-section-title px-3">QUẢN LÝ</span>
            <ul class="nav flex-column mt-2">
                <li class="nav-item">
                    <a href="admin.php?route=orders" class="nav-link <?= $currentRoute === 'orders' ? 'active' : '' ?>">
                        <i class="fa-solid fa-receipt nav-icon"></i> Đơn hàng
                    </a>
                </li>
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
                    <a href="admin.php?route=customers" class="nav-link <?= $currentRoute === 'customers' ? 'active' : '' ?>">
                        <i class="fa-solid fa-users nav-icon"></i> Khách hàng
                    </a>
                </li>
                <li class="nav-item">
                    <a href="admin.php?route=staff" class="nav-link <?= $currentRoute === 'staff' ? 'active' : '' ?>">
                        <i class="fa-solid fa-user-tie nav-icon"></i> Nhân viên
                    </a>
                </li>
                <li class="nav-item">
                    <a href="admin.php?route=tables" class="nav-link <?= $currentRoute === 'tables' ? 'active' : '' ?>">
                        <i class="fa-solid fa-chair nav-icon"></i> Bàn
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    
    <div class="sidebar-footer p-3 mt-auto border-top">
        <a href="logout.php" class="btn btn-outline-danger w-100 rounded-3">
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
        <!-- Re-use the same nav HTML -->
        <nav class="sidebar-nav flex-grow-1 p-3 overflow-y-auto">
            <div class="nav-section mb-4">
                <span class="nav-section-title px-3">DASHBOARD</span>
                <ul class="nav flex-column mt-2">
                    <li class="nav-item">
                        <a href="admin.php?route=dashboard" class="nav-link <?= $currentRoute === 'dashboard' ? 'active' : '' ?>">
                            <i class="fa-solid fa-chart-line nav-icon"></i> Dashboard
                        </a>
                    </li>
                </ul>
            </div>

            <div class="nav-section mb-4">
                <span class="nav-section-title px-3">KHO</span>
                <ul class="nav flex-column mt-2">
                    <li class="nav-item">
                        <a href="admin.php?route=inventory" class="nav-link <?= $currentRoute === 'inventory' ? 'active' : '' ?>">
                            <i class="fa-solid fa-boxes-stacked nav-icon"></i> Nguyên liệu
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="admin.php?route=product_ingredients" class="nav-link <?= $currentRoute === 'product_ingredients' ? 'active' : '' ?>">
                            <i class="fa-solid fa-blender nav-icon"></i> Công thức SP
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
                    <li class="nav-item">
                        <a href="admin.php?route=inventory_alerts" class="nav-link <?= $currentRoute === 'inventory_alerts' ? 'active' : '' ?>">
                            <i class="fa-solid fa-triangle-exclamation nav-icon"></i> Cảnh báo
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="admin.php?route=inventory_history" class="nav-link <?= $currentRoute === 'inventory_history' ? 'active' : '' ?>">
                            <i class="fa-solid fa-clock-rotate-left nav-icon"></i> Lịch sử kho
                        </a>
                    </li>
                </ul>
            </div>

            <div class="nav-section mb-4">
                <span class="nav-section-title px-3">QUẢN LÝ</span>
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
                    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
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
                        <a href="admin.php?route=customers" class="nav-link <?= $currentRoute === 'customers' ? 'active' : '' ?>">
                            <i class="fa-solid fa-users nav-icon"></i> Khách hàng
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="admin.php?route=staff" class="nav-link <?= $currentRoute === 'staff' ? 'active' : '' ?>">
                            <i class="fa-solid fa-user-tie nav-icon"></i> Nhân viên
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </div>
</div>
