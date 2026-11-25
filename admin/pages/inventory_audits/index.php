<?php
if ($action === 'create') {
    require __DIR__ . '/create.php';
    return;
}
if ($action === 'show' && !empty($audit)) {
    require __DIR__ . '/show.php';
    return;
}
?>
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="font-serif fw-bold text-dark mb-1">Kiểm kê kho nguyên liệu</h4>
        <p class="text-muted small mb-0">Đối chiếu tồn kho thực tế và theo dõi chênh lệch nguyên liệu định kỳ</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= e(admin_url('inventory')) ?>" class="btn btn-outline-secondary rounded-pill px-3">
            <i class="fa-solid fa-boxes-stacked me-1"></i> Tồn kho hiện tại
        </a>
        <a href="<?= e(admin_url('inventory_audits', ['action' => 'create'])) ?>" class="btn btn-forest rounded-pill px-4">
            <i class="fa-solid fa-clipboard-check me-2"></i> Lập phiếu kiểm kê
        </a>
    </div>
</div>

<?php if ($successMsg = get_flash('success')): ?>
    <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4">
        <i class="fa-solid fa-circle-check me-2"></i> <?= e($successMsg) ?>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if ($errorMsg = get_flash('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4">
        <i class="fa-solid fa-triangle-exclamation me-2"></i> <?= e($errorMsg) ?>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-nowrap">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-4 py-3 fw-medium border-0">Mã phiếu</th>
                        <th class="py-3 fw-medium border-0">Người kiểm kê</th>
                        <th class="py-3 fw-medium border-0 text-center">Tổng mục kiểm</th>
                        <th class="py-3 fw-medium border-0 text-center">Số mục lệch</th>
                        <th class="py-3 fw-medium border-0">Trạng thái</th>
                        <th class="py-3 fw-medium border-0">Ghi chú</th>
                        <th class="py-3 fw-medium border-0">Ngày kiểm kê</th>
                        <th class="pe-4 py-3 fw-medium border-0 text-end">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <?php if (empty($audits)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-clipboard-list fs-2 d-block mb-2 opacity-50"></i>
                                Chưa có phiếu kiểm kê kho nào. Hãy nhấn <strong>Lập phiếu kiểm kê</strong> để bắt đầu.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($audits as $a): ?>
                        <tr>
                            <td class="ps-4 fw-bold font-monospace text-forest">
                                <a href="<?= e(admin_url('inventory_audits', ['action' => 'show', 'id' => (int)$a['id']])) ?>" class="text-decoration-none text-forest">
                                    <?= e($a['audit_code']) ?>
                                </a>
                            </td>
                            <td><?= e($a['user_name']) ?></td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border px-3 py-1"><?= (int)$a['total_items'] ?> mục</span>
                            </td>
                            <td class="text-center">
                                <?php if ((int)$a['total_difference_items'] > 0): ?>
                                    <span class="badge bg-warning bg-opacity-25 text-dark rounded-pill px-3 py-1 fw-semibold">
                                        <?= (int)$a['total_difference_items'] ?> mục lệch
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1">
                                        Khớp 100%
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-success rounded-pill px-3 py-1">
                                    <?= $a['status'] === 'completed' ? 'Hoàn thành' : 'Nháp' ?>
                                </span>
                            </td>
                            <td class="text-muted small text-truncate" style="max-width: 220px;">
                                <?= e($a['note'] ?: '—') ?>
                            </td>
                            <td class="text-muted small"><?= date('d/m/Y H:i', strtotime($a['created_at'])) ?></td>
                            <td class="pe-4 text-end">
                                <a href="<?= e(admin_url('inventory_audits', ['action' => 'show', 'id' => (int)$a['id']])) ?>" class="btn btn-sm btn-outline-forest rounded-pill px-3">
                                    <i class="fa-solid fa-eye me-1"></i> Chi tiết
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
