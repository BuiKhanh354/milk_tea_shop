<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="font-serif fw-bold text-dark mb-1">Báo cáo tồn kho nguyên liệu</h4>
        <p class="text-muted small mb-0">Tổng hợp giá trị tồn kho, lượng nhập - xuất - tiêu hao và cảnh báo định mức</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= e(admin_url('inventory_audits')) ?>" class="btn btn-outline-forest rounded-pill px-3">
            <i class="fa-solid fa-clipboard-check me-1"></i> Kiểm kê kho
        </a>
        <button type="button" class="btn btn-forest rounded-pill px-4" onclick="window.print()">
            <i class="fa-solid fa-print me-2"></i> In báo cáo
        </button>
    </div>
</div>

<!-- Thống kê tổng quan kho -->
<div class="row g-3 g-xl-4 mb-4">
    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 h-100 bg-white">
            <span class="text-muted small fw-semibold text-uppercase">Tổng giá trị tồn</span>
            <h4 class="fw-bold text-forest font-monospace mt-2 mb-1"><?= money($totalInventoryValue ?? 0) ?></h4>
            <small class="text-muted">Tính theo đơn giá hiện tại</small>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 h-100 bg-white">
            <span class="text-muted small fw-semibold text-uppercase">Nguyên liệu đang có</span>
            <h4 class="fw-bold text-dark mt-2 mb-1"><?= (int)($stats['total'] ?? 0) ?> <span class="fs-6 fw-normal text-muted">mặt hàng</span></h4>
            <small class="text-success"><?= (int)($stats['in_stock'] ?? 0) ?> đạt định mức an toàn</small>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 h-100 bg-white">
            <span class="text-muted small fw-semibold text-uppercase">Tổng nhập lũy kế</span>
            <h4 class="fw-bold text-primary font-monospace mt-2 mb-1"><?= number_format($totalImportAll ?? 0, 1, '.', ',') ?></h4>
            <small class="text-muted">Tổng đơn vị đã nhập kho</small>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 h-100 bg-white">
            <span class="text-muted small fw-semibold text-uppercase">Cảnh báo tồn thấp</span>
            <h4 class="fw-bold text-danger mt-2 mb-1"><?= (int)(($stats['low_stock'] ?? 0) + ($stats['out_of_stock'] ?? 0)) ?></h4>
            <small class="text-muted">Mặt hàng cần nhập thêm</small>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header bg-white border-0 py-3 px-4">
        <h5 class="fw-bold text-forest mb-0 font-serif">Chi tiết tồn kho & biến động theo nguyên liệu</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-4 py-3 fw-medium border-0">Nguyên liệu</th>
                        <th class="py-3 fw-medium border-0 text-center">Đơn vị</th>
                        <th class="py-3 fw-medium border-0 text-end">Tồn hiện tại</th>
                        <th class="py-3 fw-medium border-0 text-end">Tối thiểu</th>
                        <th class="py-3 fw-medium border-0 text-end">Tổng nhập</th>
                        <th class="py-3 fw-medium border-0 text-end">Tổng xuất/dùng</th>
                        <th class="py-3 fw-medium border-0 text-end">Giá trị tồn</th>
                        <th class="pe-4 py-3 fw-medium border-0 text-center">Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($inventoryReportRows)): ?>
                        <tr><td colspan="8" class="text-center py-4 text-muted">Chưa có dữ liệu tồn kho.</td></tr>
                    <?php else: ?>
                        <?php foreach ($inventoryReportRows as $r): ?>
                        <?php
                        $qty = (float)$r['quantity'];
                        $min = (float)$r['min_quantity'];
                        $val = $qty * (float)$r['price'];
                        if ($qty <= 0) {
                            $stBadge = '<span class="badge bg-danger rounded-pill px-3 py-1">Hết hàng</span>';
                        } elseif ($qty <= $min) {
                            $stBadge = '<span class="badge bg-warning text-dark rounded-pill px-3 py-1">Sắp hết</span>';
                        } else {
                            $stBadge = '<span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1">Đủ hàng</span>';
                        }
                        ?>
                        <tr>
                            <td class="ps-4 fw-bold text-dark"><?= e($r['ingredient_name']) ?></td>
                            <td class="text-center"><span class="badge bg-light text-dark border"><?= e($r['unit']) ?></span></td>
                            <td class="text-end font-monospace fw-bold text-dark"><?= number_format($qty, 2, '.', ',') ?></td>
                            <td class="text-end font-monospace text-muted"><?= number_format($min, 2, '.', ',') ?></td>
                            <td class="text-end font-monospace text-success">+<?= number_format((float)$r['total_import'], 2, '.', ',') ?></td>
                            <td class="text-end font-monospace text-danger">-<?= number_format((float)$r['total_out'], 2, '.', ',') ?></td>
                            <td class="text-end font-monospace fw-bold text-forest"><?= money($val) ?></td>
                            <td class="pe-4 text-center"><?= $stBadge ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
