<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="font-serif fw-bold text-dark mb-1">
            <a href="<?= e(admin_url('inventory_audits')) ?>" class="text-decoration-none text-muted fs-5 me-2">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            Lập phiếu kiểm kê kho
        </h4>
        <p class="text-muted small mb-0">Nhập số lượng thực tế cân/đo được để đối chiếu với số lượng tồn trên hệ thống</p>
    </div>
</div>

<?php if ($errorMsg = get_flash('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4">
        <i class="fa-solid fa-triangle-exclamation me-2"></i> <?= e($errorMsg) ?>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<form action="<?= e(admin_url('inventory_audits', ['action' => 'store'])) ?>" method="POST">
    <?= csrf_field() ?>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted">
                        <tr>
                            <th class="ps-4 py-3 fw-medium border-0">Nguyên liệu</th>
                            <th class="py-3 fw-medium border-0 text-center">Đơn vị</th>
                            <th class="py-3 fw-medium border-0 text-end">Tồn hệ thống</th>
                            <th class="py-3 fw-medium border-0 text-center" style="width: 170px;">Tồn thực tế</th>
                            <th class="py-3 fw-medium border-0 text-end" style="width: 140px;">Chênh lệch</th>
                            <th class="pe-4 py-3 fw-medium border-0">Lý do chênh lệch</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($ingredients)): ?>
                            <tr><td colspan="6" class="text-center py-4 text-muted">Chưa có nguyên liệu nào trong kho.</td></tr>
                        <?php else: ?>
                            <?php foreach ($ingredients as $ing): ?>
                            <?php $sysQty = (float)$ing['quantity']; ?>
                            <tr>
                                <td class="ps-4 fw-bold text-dark"><?= e($ing['ingredient_name']) ?></td>
                                <td class="text-center"><span class="badge bg-light text-dark border"><?= e($ing['unit']) ?></span></td>
                                <td class="text-end font-monospace text-muted"><?= number_format($sysQty, 2, '.', ',') ?></td>
                                <td>
                                    <input type="number" step="0.01" min="0"
                                           name="actual_quantities[<?= (int)$ing['id'] ?>]"
                                           value="<?= htmlspecialchars((string)$sysQty) ?>"
                                           data-system="<?= $sysQty ?>"
                                           data-target="diff-<?= (int)$ing['id'] ?>"
                                           class="form-control form-control-sm text-end font-monospace audit-actual-input"
                                           required>
                                </td>
                                <td class="text-end font-monospace fw-bold" id="diff-<?= (int)$ing['id'] ?>">0.00</td>
                                <td class="pe-4">
                                    <input type="text" name="reasons[<?= (int)$ing['id'] ?>]"
                                           class="form-control form-control-sm"
                                           placeholder="VD: Hao hụt pha chế, nhập thiếu...">
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 p-4">
        <div class="row g-3 align-items-center">
            <div class="col-md-7">
                <label class="form-label small fw-semibold text-muted mb-1">Ghi chú chung của phiếu kiểm kê</label>
                <input type="text" name="note" class="form-control" placeholder="VD: Kiểm kê cuối ngày / cuối tuần...">
            </div>
            <div class="col-md-5">
                <div class="form-check mt-3 mt-md-4">
                    <input class="form-check-input" type="checkbox" name="sync_inventory" value="1" id="syncInventoryCheck" checked>
                    <label class="form-check-label fw-medium text-forest" for="syncInventoryCheck">
                        Tự động cập nhật tồn kho theo số thực tế & ghi lịch sử điều chỉnh
                    </label>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
            <a href="<?= e(admin_url('inventory_audits')) ?>" class="btn btn-light rounded-pill px-4">Hủy</a>
            <button type="submit" class="btn btn-forest rounded-pill px-5 fw-bold">
                <i class="fa-solid fa-floppy-disk me-2"></i> Hoàn tất & Lưu phiếu kiểm kê
            </button>
        </div>
    </div>
</form>

<script>
document.querySelectorAll('.audit-actual-input').forEach(function (input) {
    input.addEventListener('input', function () {
        const sysQty = parseFloat(this.dataset.system || '0');
        const actualQty = parseFloat(this.value || '0');
        const diff = actualQty - sysQty;
        const target = document.getElementById(this.dataset.target);
        if (!target) return;

        target.textContent = (diff > 0 ? '+' : '') + diff.toFixed(2);
        target.classList.remove('text-danger', 'text-success');
        if (diff < -0.001) {
            target.classList.add('text-danger');
        } else if (diff > 0.001) {
            target.classList.add('text-success');
        }
    });
});
</script>
