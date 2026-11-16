<?php
if ($action === 'open') {
    require __DIR__ . '/open.php';
    return;
}
if ($action === 'close') {
    require __DIR__ . '/close.php';
    return;
}
if ($action === 'show') {
    require __DIR__ . '/show.php';
    return;
}
?>
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="font-serif fw-bold text-dark mb-1">
            <i class="fa-solid fa-cash-register text-forest me-2"></i>Quản lý Ca làm việc & Bàn giao Két
        </h4>
        <p class="text-muted small mb-0">Kiểm soát dòng tiền mặt, doanh thu và bàn giao nguyên liệu theo từng phiên ca làm việc</p>
    </div>
    <div class="d-flex gap-2">
        <?php if (!empty($currentOpenSession)): ?>
            <a href="<?= e(admin_url('shift_sessions', ['action' => 'close'])) ?>" class="btn btn-warning rounded-pill px-4 fw-medium text-dark shadow-sm">
                <i class="fa-solid fa-lock me-1"></i> Kết ca & Bàn giao ngay
            </a>
        <?php else: ?>
            <a href="<?= e(admin_url('shift_sessions', ['action' => 'open'])) ?>" class="btn btn-forest rounded-pill px-4 fw-medium shadow-sm">
                <i class="fa-solid fa-key me-1"></i> Bắt đầu / Mở ca mới
            </a>
        <?php endif; ?>
    </div>
</div>

<?php if ($successMsg = get_flash('success')): ?>
    <div class="alert alert-success alert-dismissible fade show rounded-4 mb-4 border-0 shadow-sm" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i><?= e($successMsg) ?>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if ($errorMsg = get_flash('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show rounded-4 mb-4 border-0 shadow-sm" role="alert">
        <i class="fa-solid fa-triangle-exclamation me-2"></i><?= e($errorMsg) ?>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
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
                    <span class="fw-bold font-monospace text-dark fs-5"><?= e($currentOpenSession['session_code']) ?></span>
                </div>
                <div class="text-muted small">
                    Nhân viên: <strong class="text-dark"><?= e($currentOpenSession['user_name']) ?></strong> 
                    | Mở lúc: <strong><?= date('d/m/Y H:i', strtotime($currentOpenSession['opened_at'])) ?></strong>
                    | Tiền két ban đầu: <strong class="text-forest"><?= money($currentOpenSession['initial_cash']) ?></strong>
                </div>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= e(admin_url('orders')) ?>" class="btn btn-outline-forest rounded-pill px-3">
                <i class="fa-solid fa-cart-plus me-1"></i> Xem đơn hàng
            </a>
            <a href="<?= e(admin_url('shift_sessions', ['action' => 'close'])) ?>" class="btn btn-forest rounded-pill px-4">
                <i class="fa-solid fa-calculator me-1"></i> Chốt tiền & Kết ca
            </a>
        </div>
    </div>
</div>
<?php else: ?>
<div class="card border-0 bg-light rounded-4 p-4 mb-4 text-center">
    <div class="py-2">
        <i class="fa-solid fa-store-slash text-muted fs-1 mb-2"></i>
        <h6 class="fw-bold text-dark mb-1">Hiện tại chưa có phiên ca nào đang mở</h6>
        <p class="text-muted small mb-3">Vui lòng mở ca và xác nhận số tiền mặt ban đầu trong két.</p>
        <a href="<?= e(admin_url('shift_sessions', ['action' => 'open'])) ?>" class="btn btn-forest rounded-pill px-4 py-2 fw-medium">
            <i class="fa-solid fa-key me-1"></i> Mở ca làm việc ngay
        </a>
    </div>
</div>
<?php endif; ?>

<!-- Bảng danh sách ca -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-4 py-3 fw-medium border-0">Mã ca</th>
                        <th class="py-3 fw-medium border-0">Nhân viên</th>
                        <th class="py-3 fw-medium border-0">Thời gian</th>
                        <th class="py-3 fw-medium border-0 text-end">Tiền két đầu</th>
                        <th class="py-3 fw-medium border-0 text-end">Doanh thu ca</th>
                        <th class="py-3 fw-medium border-0 text-center">Trạng thái</th>
                        <th class="pe-4 py-3 fw-medium border-0 text-end">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <?php if (empty($sessions)): ?>
                        <tr><td colspan="7" class="text-center py-4 text-muted">Chưa có phiên ca nào.</td></tr>
                    <?php else: ?>
                        <?php foreach ($sessions as $s): ?>
                        <tr>
                            <td class="ps-4 font-monospace fw-bold text-dark">
                                <a href="<?= e(admin_url('shift_sessions', ['action' => 'show', 'id' => $s['id']])) ?>" class="text-forest text-decoration-none">
                                    <?= e($s['session_code']) ?>
                                </a>
                                <?php if (!empty($s['shift_name'])): ?>
                                    <small class="d-block text-muted"><?= e($s['shift_name']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="fw-medium text-dark"><?= e($s['user_name']) ?></span>
                                <small class="d-block text-muted"><?= (int)$s['order_count'] ?> đơn hàng</small>
                            </td>
                            <td class="small text-muted">
                                <div><i class="fa-regular fa-clock text-success me-1"></i><?= date('d/m/Y H:i', strtotime($s['opened_at'])) ?></div>
                                <?php if (!empty($s['closed_at'])): ?>
                                    <div><i class="fa-regular fa-circle-stop text-danger me-1"></i><?= date('d/m/Y H:i', strtotime($s['closed_at'])) ?></div>
                                <?php else: ?>
                                    <span class="text-warning fw-medium fst-italic">Đang hoạt động...</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end font-monospace"><?= money($s['initial_cash']) ?></td>
                            <td class="text-end font-monospace fw-bold text-forest"><?= money($s['total_sales']) ?></td>
                            <td class="text-center">
                                <?php if ($s['status'] === 'open'): ?>
                                    <span class="badge bg-success rounded-pill px-3">Đang mở</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3">Đã kết ca</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-4">
                                <?php if ($s['status'] === 'open'): ?>
                                    <a href="<?= e(admin_url('shift_sessions', ['action' => 'close'])) ?>" class="btn btn-sm btn-warning rounded-pill px-3 text-dark fw-medium">
                                        Kết ca
                                    </a>
                                <?php else: ?>
                                    <a href="<?= e(admin_url('shift_sessions', ['action' => 'show', 'id' => $s['id']])) ?>" class="btn btn-sm btn-outline-forest rounded-pill px-3">
                                        Chi tiết
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

