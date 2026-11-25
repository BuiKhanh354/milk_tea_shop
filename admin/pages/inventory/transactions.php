<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="font-serif fw-bold text-dark mb-1">Lịch sử giao dịch kho</h4>
        <p class="text-muted small mb-0">Theo dõi toàn bộ biến động nhập, xuất, tiêu hao pha chế và điều chỉnh kiểm kê</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= e(admin_url('inventory_import')) ?>" class="btn btn-outline-success rounded-pill px-3">
            <i class="fa-solid fa-box-open me-1"></i> Nhập kho
        </a>
        <a href="<?= e(admin_url('inventory_export')) ?>" class="btn btn-outline-danger rounded-pill px-3">
            <i class="fa-solid fa-truck-fast me-1"></i> Xuất kho
        </a>
    </div>
</div>

<!-- Bộ lọc -->
<div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
    <form action="<?= e(admin_url('inventory_transactions')) ?>" method="GET" class="row g-3 align-items-end">
        <input type="hidden" name="page" value="inventory_transactions">
        <div class="col-md-5">
            <label class="form-label small fw-semibold text-muted mb-1">Tìm kiếm</label>
            <input type="text" name="search" class="form-control bg-light border-0 shadow-none" placeholder="Tên nguyên liệu hoặc ghi chú..." value="<?= e($transSearch ?? '') ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label small fw-semibold text-muted mb-1">Loại giao dịch</label>
            <select name="type" class="form-select bg-light border-0 shadow-none">
                <option value="">Tất cả loại giao dịch</option>
                <option value="IMPORT" <?= ($transType ?? '') === 'IMPORT' ? 'selected' : '' ?>>Nhập kho (IMPORT)</option>
                <option value="EXPORT" <?= ($transType ?? '') === 'EXPORT' ? 'selected' : '' ?>>Xuất kho (EXPORT)</option>
                <option value="USAGE" <?= ($transType ?? '') === 'USAGE' ? 'selected' : '' ?>>Tiêu hao đơn hàng (USAGE)</option>
                <option value="ADJUSTMENT" <?= ($transType ?? '') === 'ADJUSTMENT' ? 'selected' : '' ?>>Điều chỉnh kiểm kê (ADJUSTMENT)</option>
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-forest rounded-3 w-100"><i class="fa-solid fa-filter me-1"></i> Lọc</button>
            <a href="<?= e(admin_url('inventory_transactions')) ?>" class="btn btn-outline-secondary rounded-3" title="Đặt lại"><i class="fa-solid fa-rotate-left"></i></a>
        </div>
    </form>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-4 py-3 fw-medium border-0">Thời gian</th>
                        <th class="py-3 fw-medium border-0">Nguyên liệu</th>
                        <th class="py-3 fw-medium border-0 text-center">Loại</th>
                        <th class="py-3 fw-medium border-0 text-end">Số lượng</th>
                        <th class="py-3 fw-medium border-0 text-end">Đơn giá</th>
                        <th class="py-3 fw-medium border-0">Người thực hiện</th>
                        <th class="pe-4 py-3 fw-medium border-0">Ghi chú</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($transactions)): ?>
                        <tr><td colspan="7" class="text-center py-5 text-muted">Chưa có giao dịch kho nào phù hợp.</td></tr>
                    <?php else: ?>
                        <?php foreach ($transactions as $t): ?>
                        <?php
                        $typeMeta = [
                            'IMPORT'     => ['Nhập kho', 'bg-success bg-opacity-10 text-success', '+'],
                            'EXPORT'     => ['Xuất kho', 'bg-danger bg-opacity-10 text-danger', '-'],
                            'USAGE'      => ['Tiêu hao', 'bg-warning bg-opacity-20 text-dark', '-'],
                            'ADJUSTMENT' => ['Kiểm kê', 'bg-info bg-opacity-15 text-dark', ''],
                        ];
                        $meta = $typeMeta[$t['type']] ?? [$t['type'], 'bg-secondary text-white', ''];
                        ?>
                        <tr>
                            <td class="ps-4 text-muted small"><?= date('d/m/Y H:i', strtotime($t['created_at'])) ?></td>
                            <td class="fw-bold text-dark"><?= e($t['ingredient_name']) ?></td>
                            <td class="text-center">
                                <span class="badge <?= $meta[1] ?> rounded-pill px-3 py-1 fw-medium"><?= $meta[0] ?></span>
                            </td>
                            <td class="text-end font-monospace fw-bold">
                                <?= $meta[2] . number_format((float)$t['quantity'], 2, '.', ',') ?> <?= e($t['unit']) ?>
                            </td>
                            <td class="text-end font-monospace text-muted"><?= money($t['price']) ?></td>
                            <td><?= e($t['user_name']) ?></td>
                            <td class="pe-4 text-muted small"><?= e($t['note'] ?: '—') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
