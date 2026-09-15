<header class="admin-topbar d-flex align-items-center justify-content-between px-4">
    <!-- Left side -->
    <div class="d-flex align-items-center gap-3">
        <button class="btn btn-light d-lg-none btn-menu-toggle" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
            <i class="fa-solid fa-bars"></i>
        </button>
        
        <nav aria-label="breadcrumb" class="d-none d-md-block">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item text-muted">Quản lý</li>
                <li class="breadcrumb-item active fw-medium text-dark" aria-current="page"><?= $pageTitle ?? 'Dashboard' ?></li>
            </ol>
        </nav>
    </div>

    <!-- Right side -->
    <div class="d-flex align-items-center gap-3">
        <div class="search-box d-none d-md-flex align-items-center bg-light rounded-pill px-3 py-2">
            <i class="fa-solid fa-magnifying-glass text-muted"></i>
            <input type="text" class="border-0 bg-transparent ms-2" placeholder="Tìm kiếm..." style="outline: none;">
        </div>
        
        <button class="btn btn-light rounded-circle position-relative btn-notification">
            <i class="fa-regular fa-bell"></i>
            <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                <span class="visually-hidden">New alerts</span>
            </span>
        </button>
        
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="https://ui-avatars.com/api/?name=Admin&background=263A30&color=fff" alt="User" width="36" height="36" class="rounded-circle me-2">
                <div class="d-none d-md-block text-start lh-1">
                    <span class="d-block fw-bold fs-6"><?= $_SESSION['full_name'] ?? 'Admin' ?></span>
                    <span class="text-muted" style="font-size: 0.75rem;"><?= ucfirst($_SESSION['role'] ?? 'Administrator') ?></span>
                </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="userDropdown">
                <li><a class="dropdown-item" href="#"><i class="fa-regular fa-user me-2"></i> Hồ sơ</a></li>
                <li><a class="dropdown-item" href="#"><i class="fa-solid fa-gear me-2"></i> Cài đặt</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="logout.php"><i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Đăng xuất</a></li>
            </ul>
        </div>
    </div>
</header>
