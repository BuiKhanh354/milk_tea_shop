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
            <input type="text" class="border-0 bg-transparent ms-2 admin-style-874267" placeholder="Tìm kiếm..." >
        </div>
        
        <!-- Shift Session Status Badge -->
        <?php
            $topbarUserId = isset($_SESSION['user_id']) ? (is_array($_SESSION['user_id']) ? (int)current($_SESSION['user_id']) : (int)$_SESSION['user_id']) : 0;
            $topbarIsAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
            require_once __DIR__ . '/../../../models/ShiftSession.php';
            require_once __DIR__ . '/../../../models/Shift.php';
            $topbarShiftModel = new ShiftSession();
            $topbarOpenSession = $topbarShiftModel->getCurrentOpenSession($topbarUserId);
            $topbarTodayShift = null;
            if (!$topbarOpenSession) {
                $tShiftObj = new Shift();
                $topbarTodayShift = $tShiftObj->getActiveAssignedShiftForStaffNow($topbarUserId);
            }
        ?>
        <?php if (!empty($topbarOpenSession)): ?>
            <a href="admin.php?route=shift_sessions&action=close" class="btn btn-sm btn-success rounded-pill px-3 py-1 shadow-sm d-flex align-items-center gap-2 text-decoration-none" title="Nhấn để kết ca & bàn giao">
                <span class="spinner-grow spinner-grow-sm text-light" style="width: 8px; height: 8px;"></span>
                <span class="small fw-bold">Ca: <?= htmlspecialchars($topbarOpenSession['shift_name'] ?? $topbarOpenSession['session_code']) ?></span>
                <span class="badge bg-white text-success rounded-pill small py-1 px-2">Kết ca</span>
            </a>
        <?php elseif ($topbarTodayShift): ?>
            <a href="admin.php?route=shift_sessions&action=open" class="btn btn-sm btn-outline-forest rounded-pill px-3 py-1 shadow-sm d-flex align-items-center gap-2 text-decoration-none" title="Hôm nay bạn có ca: <?= htmlspecialchars($topbarTodayShift['shift_name']) ?>">
                <i class="fa-solid fa-business-time text-forest"></i>
                <span class="small fw-bold">Vào ca: <?= htmlspecialchars($topbarTodayShift['shift_name']) ?> (<?= substr($topbarTodayShift['start_time'], 0, 5) ?>)</span>
                <span class="badge bg-forest text-white rounded-pill small py-1 px-2">Mở ca</span>
            </a>
        <?php elseif ($topbarIsAdmin): ?>
            <a href="admin.php?route=shift_sessions&action=open" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 shadow-sm d-flex align-items-center gap-2 text-decoration-none" title="Mở ca làm việc">
                <i class="fa-solid fa-lock-open text-muted"></i>
                <span class="small fw-bold">Mở ca</span>
            </a>
        <?php else: ?>
            <span class="badge bg-light text-muted border rounded-pill px-3 py-2 small" title="Không có lịch phân công ca hôm nay">
                <i class="fa-solid fa-calendar-xmark me-1 text-secondary"></i> Chưa có ca hôm nay
            </span>
        <?php endif; ?>

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
                    <span class="text-muted admin-style-af89d6" ><?= ucfirst($_SESSION['role'] ?? 'Administrator') ?></span>
                </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="userDropdown">
                <li><a class="dropdown-item" href="admin.php?route=profile"><i class="fa-regular fa-user me-2"></i> Hồ sơ</a></li>
                <li><a class="dropdown-item" href="#"><i class="fa-solid fa-gear me-2"></i> Cài đặt</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="index.php?route=logout"><i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Đăng xuất</a></li>
            </ul>
        </div>
    </div>
</header>
