<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="font-serif fw-bold text-dark mb-1">
            <i class="fa-solid fa-cash-register text-forest me-2"></i>Quản lý Ca làm việc & Bàn giao Két
        </h4>
        <p class="text-muted small mb-0">Kiểm soát dòng tiền mặt, doanh thu và bàn giao nguyên liệu theo từng phiên ca làm việc</p>
    </div>
    <div class="d-flex gap-2">
        <?php if (!empty($currentOpenSession)): ?>
            <a href="admin.php?route=shift_sessions&action=close" class="btn btn-warning rounded-pill px-4 fw-medium text-dark shadow-sm">
                <i class="fa-solid fa-lock me-1"></i> Kết ca & Bàn giao ngay
            </a>
        <?php else: ?>
            <a href="admin.php?route=shift_sessions&action=open" class="btn btn-forest rounded-pill px-4 fw-medium shadow-sm">
                <i class="fa-solid fa-key me-1"></i> Bắt đầu / Mở ca mới
            </a>
        <?php endif; ?>
    </div>
</div>

<?php if (isset($_SESSION['flash_success'])): ?>
    <div class="alert alert-success alert-dismissible fade show rounded-4 mb-4" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i><?= $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['flash_error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show rounded-4 mb-4" role="alert">
        <i class="fa-solid fa-triangle-exclamation me-2"></i><?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Banner Ca Hiện Tại -->
<?php if (!empty($currentOpenSession)): ?>
<div class="card border-0 bg-success bg-opacity-10 border-success border-start border-4 rounded-4 p-4 mb-4 shadow-sm">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="d-flex align-items-center">
            <div class="bg-success text-white p-3 rounded-circle me-3">
                <i class="fa-solid fa-door-open fs-4"></i>
            </div>
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge bg-success rounded-pill px-3 py-1">ĐANG MỞ CA</span>
                    <span class="fw-bold font-monospace text-dark fs-5"><?= htmlspecialchars($currentOpenSession['session_code']) ?></span>
                </div>
                <div class="text-muted small">
                    Nhân viên: <strong class="text-dark"><?= htmlspecialchars($currentOpenSession['user_name']) ?></strong> 
                    | Mở lúc: <strong><?= date('d/m/Y H:i', strtotime($currentOpenSession['opened_at'])) ?></strong>
                    | Tiền két ban đầu: <strong class="text-forest"><?= number_format($currentOpenSession['initial_cash'], 0, ',', '.') ?>đ</strong>
                </div>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="admin.php?route=orders&action=create" class="btn btn-outline-forest rounded-pill px-3">
                <i class="fa-solid fa-cart-plus me-1"></i> Vào bán hàng POS
            </a>
            <a href="admin.php?route=shift_sessions&action=close" class="btn btn-forest rounded-pill px-4">
                <i class="fa-solid fa-calculator me-1"></i> Chốt tiền & Kết ca
            </a>
        </div>
    </div>
</div>
<?php else: ?>
<div class="card border-0 bg-light rounded-4 p-4 mb-4 text-center">
    <div class="py-2">
        <i class="fa-solid fa-store-slash text-muted fs-1 mb-2"></i>
        <h6 class="fw-bold text-dark mb-1">Hiện tại bạn chưa mở phiên ca làm việc</h6>
        <p class="text-muted small mb-3">Vui lòng mở ca và xác nhận số tiền mặt ban đầu trong két trước khi nhận đơn tại POS.</p>
        <a href="admin.php?route=shift_sessions&action=open" class="btn btn-forest rounded-pill px-4 py-2 fw-medium">
            <i class="fa-solid fa-key me-1"></i> Mở ca làm việc ngay
        </a>
    </div>
</div>
<?php endif; ?>

<!-- Bộ lọc danh sách ca -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-3">
        <form method="GET" action="admin.php" class="row g-2 align-items-center">
            <input type="hidden" name="route" value="shift_sessions">
            
            <div class="col-md-3">
                <input type="date" name="date" class="form-control form-control-sm bg-light border-0" value="<?= htmlspecialchars($_GET['date'] ?? '') ?>" placeholder="Chọn ngày...">
            </div>

            <?php if (!empty($users)): ?>
            <div class="col-md-3">
                <select name="user_id" class="form-select form-select-sm bg-light border-0">
                    <option value="">-- Tất cả nhân viên --</option>
                    <?php foreach ($users as $u): ?>
                        <option value="<?= $u['id'] ?>" <?= (isset($_GET['user_id']) && $_GET['user_id'] == $u['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($u['full_name']) ?> (<?= htmlspecialchars($u['username']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>

            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm bg-light border-0">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="open" <?= (isset($_GET['status']) && $_GET['status'] === 'open') ? 'selected' : '' ?>>Đang mở</option>
                    <option value="closed" <?= (isset($_GET['status']) && $_GET['status'] === 'closed') ? 'selected' : '' ?>>Đã kết ca</option>
                </select>
            </div>

            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-forest rounded-pill px-3 w-100">
                    <i class="fa-solid fa-filter me-1"></i> Lọc ca
                </button>
                <a href="admin.php?route=shift_sessions" class="btn btn-sm btn-light border rounded-pill px-3">
                    Đặt lại
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Bảng Lịch sử các Ca -->
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <h6 class="fw-bold text-dark mb-3">Lịch sử các phiên ca làm việc</h6>

        <?php if (empty($sessions)): ?>
            <div class="text-center text-muted py-5">
                <i class="fa-solid fa-receipt fs-1 mb-3 text-secondary opacity-50"></i>
                <p class="mb-0">Chưa có phiên ca làm việc nào phù hợp với bộ lọc.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light text-muted small">
                        <tr>
                            <th class="py-3 px-3">Mã phiên ca</th>
                            <th class="py-3">Nhân viên</th>
                            <th class="py-3">Thời gian mở - đóng</th>
                            <th class="py-3 text-end">Tiền két đầu ca</th>
                            <th class="py-3 text-end">Doanh thu bán</th>
                            <th class="py-3 text-end">Tiền thực tế cuối</th>
                            <th class="py-3 text-center">Chênh lệch tiền</th>
                            <th class="py-3 text-center">Trạng thái</th>
                            <th class="py-3 text-end px-3">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sessions as $s): 
                            $cashDiff = (float)($s['cash_difference'] ?? 0);
                        ?>
                        <tr>
                            <td class="px-3 fw-bold">
                                <a href="admin.php?route=shift_sessions&action=show&id=<?= $s['id'] ?>" class="text-forest text-decoration-none font-monospace">
                                    <?= htmlspecialchars($s['session_code']) ?>
                                </a>
                                <?php if (!empty($s['shift_name'])): ?>
                                    <small class="d-block text-muted"><?= htmlspecialchars($s['shift_name']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="fw-medium text-dark"><?= htmlspecialchars($s['user_name']) ?></span>
                                <small class="d-block text-muted"><?= $s['order_count'] ?> đơn hàng</small>
                            </td>
                            <td class="small text-muted">
                                <div><i class="fa-regular fa-clock text-success me-1"></i><?= date('d/m/Y H:i', strtotime($s['opened_at'])) ?></div>
                                <?php if (!empty($s['closed_at'])): ?>
                                    <div><i class="fa-regular fa-circle-stop text-danger me-1"></i><?= date('d/m/Y H:i', strtotime($s['closed_at'])) ?></div>
                                <?php else: ?>
                                    <span class="text-warning fw-medium fst-italic">Đang hoạt động...</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end font-monospace">
                                <?= number_format($s['initial_cash'], 0, ',', '.') ?>đ
                            </td>
                            <td class="text-end font-monospace fw-bold text-forest">
                                <?= number_format($s['total_sales'], 0, ',', '.') ?>đ
                            </td>
                            <td class="text-end font-monospace">
                                <?php if ($s['status'] === 'closed'): ?>
                                    <?= number_format($s['actual_cash'], 0, ',', '.') ?>đ
                                <?php else: ?>
                                    <span class="text-muted small fst-italic">Chưa chốt</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center font-monospace">
                                <?php if ($s['status'] === 'closed'): ?>
                                    <?php if (abs($cashDiff) < 1): ?>
                                        <span class="badge bg-success bg-opacity-15 text-light rounded-pill px-3">Khớp (0đ)</span>
                                    <?php elseif ($cashDiff < 0): ?>
                                        <span class="badge bg-danger rounded-pill px-3" title="<?= htmlspecialchars($s['cash_difference_reason'] ?? '') ?>">
                                            Thiếu <?= number_format($cashDiff, 0, ',', '.') ?>đ
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark rounded-pill px-3" title="<?= htmlspecialchars($s['cash_difference_reason'] ?? '') ?>">
                                            Thừa +<?= number_format($cashDiff, 0, ',', '.') ?>đ
                                        </span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-muted small">--</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if ($s['status'] === 'open'): ?>
                                    <span class="badge bg-success rounded-pill px-3">Đang mở</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary rounded-pill px-3">Đã kết ca</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end px-3">
                                <?php if ($s['status'] === 'open'): ?>
                                    <a href="admin.php?route=shift_sessions&action=close" class="btn btn-sm btn-warning rounded-pill px-3 text-dark fw-medium">
                                        Kết ca
                                    </a>
                                <?php else: ?>
                                    <a href="admin.php?route=shift_sessions&action=show&id=<?= $s['id'] ?>" class="btn btn-sm btn-outline-forest rounded-pill px-3">
                                        Chi tiết
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
