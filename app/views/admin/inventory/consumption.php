<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="font-serif fw-bold text-dark mb-1">
            <i class="fa-solid fa-chart-line text-forest me-2"></i>Theo dõi Tiêu hao & Hao hụt Nguyên liệu
        </h4>
        <p class="text-muted small mb-0">Đối soát lượng nguyên liệu dự kiến (theo công thức chuẩn) với lượng sử dụng thực tế để phát hiện vượt định mức</p>
    </div>
    <div class="d-flex gap-2">
        <a href="admin.php?route=inventory_audit" class="btn btn-forest rounded-pill px-3">
            <i class="fa-solid fa-clipboard-check me-1"></i> Kiểm kê kho
        </a>
        <a href="admin.php?route=product_ingredients" class="btn btn-outline-secondary rounded-pill px-3">
            <i class="fa-solid fa-blender me-1"></i> Định mức công thức
        </a>
    </div>
</div>

<!-- Bộ lọc thời gian -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-3">
        <form method="GET" action="admin.php" class="row g-2 align-items-center">
            <input type="hidden" name="route" value="inventory_consumption">
            
            <div class="col-auto">
                <span class="text-muted small fw-medium"><i class="fa-regular fa-calendar me-1"></i>Khoảng thời gian:</span>
            </div>
            <div class="col-auto">
                <input type="date" name="from_date" class="form-control form-control-sm bg-light border-0" value="<?= htmlspecialchars($report['from_date']) ?>">
            </div>
            <div class="col-auto text-muted small">đến</div>
            <div class="col-auto">
                <input type="date" name="to_date" class="form-control form-control-sm bg-light border-0" value="<?= htmlspecialchars($report['to_date']) ?>">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-forest rounded-pill px-3">
                    <i class="fa-solid fa-filter me-1"></i> Lọc báo cáo
                </button>
            </div>
            <div class="col-auto ms-auto d-flex gap-1">
                <a href="admin.php?route=inventory_consumption&from_date=<?= date('Y-m-d') ?>&to_date=<?= date('Y-m-d') ?>" class="btn btn-sm btn-light border rounded-pill px-2">Hôm nay</a>
                <a href="admin.php?route=inventory_consumption&from_date=<?= date('Y-m-d', strtotime('-7 days')) ?>&to_date=<?= date('Y-m-d') ?>" class="btn btn-sm btn-light border rounded-pill px-2">7 ngày qua</a>
                <a href="admin.php?route=inventory_consumption&from_date=<?= date('Y-m-01') ?>&to_date=<?= date('Y-m-d') ?>" class="btn btn-sm btn-light border rounded-pill px-2">Tháng này</a>
            </div>
        </form>
    </div>
</div>

<!-- Thống kê tổng quan -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <span class="text-muted small">Đơn hàng hoàn thành</span>
            <h4 class="fw-bold text-dark mt-1 mb-0"><?= number_format($report['order_count']) ?> đơn</h4>
            <small class="text-muted mt-1"><?= number_format($report['product_count']) ?> ly / món được pha chế</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <span class="text-muted small">Nguyên liệu vượt định mức</span>
            <h4 class="fw-bold <?= $report['over_count'] > 0 ? 'text-danger' : 'text-success' ?> mt-1 mb-0">
                <?= $report['over_count'] ?> nguyên liệu
            </h4>
            <small class="text-muted mt-1"><?= $report['over_count'] > 0 ? 'Cần kiểm tra lại pha chế' : 'Đạt chuẩn công thức' ?></small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <span class="text-muted small">Ước tính giá trị vượt định mức</span>
            <h4 class="fw-bold <?= $report['total_loss_value'] > 0 ? 'text-danger' : 'text-muted' ?> mt-1 mb-0">
                <?= number_format($report['total_loss_value'], 0, ',', '.') ?>đ
            </h4>
            <small class="text-muted mt-1">Tính theo đơn giá nguyên liệu</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <span class="text-muted small">Quy tắc định mức</span>
            <div class="small text-muted mt-2">
                <i class="fa-solid fa-circle-info text-primary me-1"></i> Tiêu hao dự kiến = Số ly bán × Định mức size & mức đường
            </div>
        </div>
    </div>
</div>

<!-- Bảng so sánh Tiêu hao dự kiến vs Thực tế -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold text-dark mb-0">Bảng đối soát tiêu hao nguyên liệu</h6>
            <span class="text-muted small">Từ ngày <b><?= date('d/m/Y', strtotime($report['from_date'])) ?></b> đến <b><?= date('d/m/Y', strtotime($report['to_date'])) ?></b></span>
        </div>

        <div class="table-responsive">
            <table class="table align-middle table-hover">
                <thead class="bg-light text-muted small">
                    <tr>
                        <th class="py-3 px-3">#</th>
                        <th class="py-3">Nguyên liệu</th>
                        <th class="py-3 text-center">ĐVT</th>
                        <th class="py-3 text-end">Tồn hiện tại</th>
                        <th class="py-3 text-end">Dự kiến tiêu hao (Công thức)</th>
                        <th class="py-3 text-end">Thực tế đã trừ/dùng</th>
                        <th class="py-3 text-center">Chênh lệch</th>
                        <th class="py-3 text-center">Tỷ lệ lệch</th>
                        <th class="py-3 text-center">Trạng thái đánh giá</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $hasData = false;
                    foreach ($report['items'] as $idx => $row): 
                        if ($row['expected_qty'] > 0 || $row['actual_qty'] > 0) {
                            $hasData = true;
                        }
                        $diff = (float)$row['difference_qty'];
                    ?>
                    <tr>
                        <td class="px-3 text-muted"><?= $idx + 1 ?></td>
                        <td>
                            <span class="fw-bold text-dark"><?= htmlspecialchars($row['ingredient_name']) ?></span>
                            <small class="text-muted d-block"><?= number_format($row['price'], 0, ',', '.') ?>đ/<?= htmlspecialchars($row['unit']) ?></small>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark border"><?= htmlspecialchars($row['unit']) ?></span>
                        </td>
                        <td class="text-end font-monospace">
                            <?= number_format($row['current_stock'], 2, '.', '') ?>
                        </td>
                        <td class="text-end font-monospace fw-medium text-dark">
                            <?= number_format($row['expected_qty'], 2, '.', '') ?>
                        </td>
                        <td class="text-end font-monospace fw-bold <?= $row['actual_qty'] > 0 ? 'text-primary' : 'text-muted' ?>">
                            <?= number_format($row['actual_qty'], 2, '.', '') ?>
                            <?php if ($row['loss_export_qty'] > 0): ?>
                                <small class="d-block text-danger font-monospace" title="Số lượng xuất hủy / hao hụt ghi nhận">
                                    (Hủy: <?= number_format($row['loss_export_qty'], 2, '.', '') ?>)
                                </small>
                            <?php endif; ?>
                        </td>
                        <td class="text-center font-monospace">
                            <?php if (abs($diff) < 0.001): ?>
                                <span class="text-success fw-bold">0.00</span>
                            <?php elseif ($diff > 0): ?>
                                <span class="text-danger fw-bold">+<?= number_format($diff, 2, '.', '') ?></span>
                            <?php else: ?>
                                <span class="text-success font-monospace"><?= number_format($diff, 2, '.', '') ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center font-monospace small">
                            <?php if (abs($row['variance_percent']) < 0.01): ?>
                                <span class="text-muted">0%</span>
                            <?php elseif ($row['variance_percent'] > 0): ?>
                                <span class="text-danger fw-bold">+<?= number_format($row['variance_percent'], 1) ?>%</span>
                            <?php else: ?>
                                <span class="text-success"><?= number_format($row['variance_percent'], 1) ?>%</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php if ($row['status'] === 'over'): ?>
                                <span class="badge bg-danger bg-opacity-15 text-danger rounded-pill px-3 py-2">
                                    <i class="fa-solid fa-arrow-trend-up me-1"></i> Vượt định mức
                                </span>
                            <?php elseif ($row['expected_qty'] == 0 && $row['actual_qty'] == 0): ?>
                                <span class="badge bg-light text-muted rounded-pill px-3 py-2">
                                    Không phát sinh
                                </span>
                            <?php else: ?>
                                <span class="badge bg-success bg-opacity-15 text-success rounded-pill px-3 py-2">
                                    <i class="fa-solid fa-check me-1"></i> Trong định mức
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Hướng dẫn & Giải thích nghiệp vụ theo quy định chuẩn -->
<div class="card border-0 bg-light rounded-4 p-4">
    <h6 class="fw-bold text-dark mb-2"><i class="fa-solid fa-book-open text-forest me-2"></i>Quy định nghiệp vụ quản lý kho & định mức (VAA THÉ)</h6>
    <div class="row g-3 small text-muted">
        <div class="col-md-4">
            <strong class="text-dark d-block mb-1">1. Vượt định mức tiêu hao</strong>
            Lượng nguyên liệu thực tế pha chế nhiều hơn lượng công thức chuẩn quy định (ví dụ múc quá tay, múc dư topping, pha đậm hơn công thức).
        </div>
        <div class="col-md-4">
            <strong class="text-dark d-block mb-1">2. Hao hụt nguyên liệu</strong>
            Nguyên liệu bị mất ngoài dự kiến do rơi vãi, làm đổ, quá hạn sử dụng hoặc bảo quản hư hỏng. Cần thực hiện "Xuất kho" và chọn lý do tương ứng.
        </div>
        <div class="col-md-4">
            <strong class="text-dark d-block mb-1">3. Chênh lệch tồn kho</strong>
            Số lượng đếm thực tế khi kiểm kê khác với số lượng trên hệ thống ghi nhận. Cần thực hiện "Kiểm kê kho" định kỳ để cân bằng lại số liệu.
        </div>
    </div>
</div>
