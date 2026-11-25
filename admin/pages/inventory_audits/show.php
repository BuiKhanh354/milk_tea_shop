<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="font-serif fw-bold text-dark mb-1">
            <a href="<?= e(admin_url('inventory_audits')) ?>" class="text-decoration-none text-muted fs-5 me-2">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            Chi tiết phiếu kiểm kê <?= e($audit['audit_code']) ?>
        </h4>
        <p class="text-muted small mb-0">
            Người thực hiện: <strong><?= e($audit['user_name']) ?></strong> • Thời gian: <?= date('d/m/Y H:i', strtotime($audit['created_at'])) ?>
        </p>
    </div>
    <button type="button" class="btn btn-outline-forest rounded-pill px-4" onclick="window.print()">
        <i class="fa-solid fa-print me-2"></i> In phiếu kiểm kê
    </button>
</div>

<?php if ($successMsg = get_flash('success')): ?>
    <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4">
        <i class="fa-solid fa-circle-check me-2"></i> <?= e($successMsg) ?>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
            <span class="text-muted small text-uppercase fw-semibold">Tổng nguyên liệu kiểm</span>
            <h4 class="fw-bold text-dark mt-1 mb-0"><?= (int)$audit['total_items'] ?> mục</h4>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
            <span class="text-muted small text-uppercase fw-semibold">Số mục có chênh lệch</span>
            <h4 class="fw-bold <?= (int)$audit['total_difference_items'] > 0 ? 'text-warning' : 'text-success' ?> mt-1 mb-0">
                <?= (int)$audit['total_difference_items'] ?> mục
            </h4>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
            <span class="text-muted small text-uppercase fw-semibold">Ghi chú phiếu</span>
            <div class="text-dark mt-1"><?= e($audit['note'] ?: 'Không có ghi chú') ?></div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-4 py-3 fw-medium border-0">Nguyên liệu</th>
                        <th class="py-3 fw-medium border-0 text-center">Đơn vị</th>
                        <th class="py-3 fw-medium border-0 text-end">Tồn hệ thống</th>
                        <th class="py-3 fw-medium border-0 text-end">Tồn thực tế</th>
                        <th class="py-3 fw-medium border-0 text-end">Chênh lệch</th>
                        <th class="pe-4 py-3 fw-medium border-0">Lý do</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($auditDetails)): ?>
                        <tr><td colspan="6" class="text-center py-4 text-muted">Phiếu kiểm kê chưa có chi tiết.</td></tr>
                    <?php else: ?>
                        <?php foreach ($auditDetails as $d): ?>
                        <?php $diff = (float)$d['difference_quantity']; ?>
                        <tr>
                            <td class="ps-4 fw-bold text-dark"><?= e($d['ingredient_name']) ?></td>
                            <td class="text-center"><span class="badge bg-light text-dark border"><?= e($d['unit']) ?></span></td>
                            <td class="text-end font-monospace text-muted"><?= number_format((float)$d['system_quantity'], 2, '.', ',') ?></td>
                            <td class="text-end font-monospace fw-bold"><?= number_format((float)$d['actual_quantity'], 2, '.', ',') ?></td>
                            <td class="text-end font-monospace fw-bold <?= $diff < 0 ? 'text-danger' : ($diff > 0 ? 'text-success' : 'text-muted') ?>">
                                <?= ($diff > 0 ? '+' : '') . number_format($diff, 2, '.', ',') ?>
                            </td>
                            <td class="pe-4 text-muted small"><?= e($d['reason'] ?: '—') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
