<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="font-serif fw-bold text-dark mb-1">
            <a href="admin.php?route=inventory_audit" class="text-muted me-2"><i class="fa-solid fa-arrow-left"></i></a>
            Phiếu kiểm kê #<?= htmlspecialchars($audit['audit_code']) ?>
        </h4>
        <p class="text-muted small mb-0">Thời gian tạo: <?= date('d/m/Y H:i:s', strtotime($audit['created_at'])) ?> | Người thực hiện: <?= htmlspecialchars($audit['user_name']) ?></p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-light border rounded-pill px-3" onclick="window.print()">
            <i class="fa-solid fa-print me-1"></i> In phiếu
        </button>
        <a href="admin.php?route=inventory_audit" class="btn btn-forest rounded-pill px-3">
            <i class="fa-solid fa-plus me-1"></i> Tạo phiếu mới
        </a>
    </div>
</div>

<?php 
    $matchCount = 0;
    $lossCount = 0;
    $surplusCount = 0;
    $totalVarianceValue = 0;

    foreach ($details as $d) {
        $diff = (float)$d['difference_quantity'];
        if (abs($diff) < 0.001) {
            $matchCount++;
        } elseif ($diff < 0) {
            $lossCount++;
            $totalVarianceValue += abs($diff) * (float)$d['price'];
        } else {
            $surplusCount++;
        }
    }
?>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
            <span class="text-muted small">Tổng nguyên liệu kiểm</span>
            <h4 class="fw-bold text-dark mt-1 mb-0"><?= count($details) ?></h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
            <span class="text-muted small">Nguyên liệu khớp 100%</span>
            <h4 class="fw-bold text-success mt-1 mb-0"><?= $matchCount ?></h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
            <span class="text-muted small">Nguyên liệu thiếu hụt (-)</span>
            <h4 class="fw-bold text-danger mt-1 mb-0"><?= $lossCount ?></h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
            <span class="text-muted small">Ước tính giá trị thiếu hụt</span>
            <h4 class="fw-bold text-danger mt-1 mb-0"><?= number_format($totalVarianceValue, 0, ',', '.') ?>đ</h4>
        </div>
    </div>
</div>

<!-- Details Table -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold text-dark mb-0">Chi tiết đối chiếu từng nguyên liệu</h6>
            <?php if (!empty($audit['note'])): ?>
                <div class="badge bg-light text-muted border py-2 px-3 fw-normal">
                    <i class="fa-regular fa-comment-dots me-1"></i> Ghi chú: <?= htmlspecialchars($audit['note']) ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="table-responsive">
            <table class="table align-middle table-hover">
                <thead class="bg-light text-muted small">
                    <tr>
                        <th class="py-3 px-3">#</th>
                        <th class="py-3">Tên nguyên liệu</th>
                        <th class="py-3 text-center">ĐVT</th>
                        <th class="py-3 text-end">Tồn hệ thống</th>
                        <th class="py-3 text-end">Thực tế kiểm kê</th>
                        <th class="py-3 text-center">Chênh lệch</th>
                        <th class="py-3 text-end">Đơn giá</th>
                        <th class="py-3 text-end">Giá trị lệch</th>
                        <th class="py-3">Lý do / Giải trình</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($details as $idx => $row): 
                        $diff = (float)$row['difference_quantity'];
                        $price = (float)$row['price'];
                        $valDiff = $diff * $price;
                    ?>
                    <tr>
                        <td class="px-3 text-muted"><?= $idx + 1 ?></td>
                        <td>
                            <span class="fw-bold text-dark"><?= htmlspecialchars($row['ingredient_name']) ?></span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark border"><?= htmlspecialchars($row['unit']) ?></span>
                        </td>
                        <td class="text-end font-monospace">
                            <?= number_format($row['system_quantity'], 2, '.', '') ?>
                        </td>
                        <td class="text-end font-monospace fw-bold text-primary">
                            <?= number_format($row['actual_quantity'], 2, '.', '') ?>
                        </td>
                        <td class="text-center">
                            <?php if (abs($diff) < 0.001): ?>
                                <span class="badge bg-success bg-opacity-15 text-success rounded-pill px-3">Khớp (0)</span>
                            <?php elseif ($diff < 0): ?>
                                <span class="badge bg-danger rounded-pill px-3 font-monospace"><?= number_format($diff, 2, '.', '') ?></span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark rounded-pill px-3 font-monospace">+<?= number_format($diff, 2, '.', '') ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end text-muted small">
                            <?= number_format($price, 0, ',', '.') ?>đ
                        </td>
                        <td class="text-end font-monospace fw-medium <?= $valDiff < 0 ? 'text-danger' : ($valDiff > 0 ? 'text-success' : 'text-muted') ?>">
                            <?= number_format($valDiff, 0, ',', '.') ?>đ
                        </td>
                        <td class="small">
                            <?php if (!empty($row['reason'])): ?>
                                <span class="text-dark bg-light p-1 px-2 rounded border"><?= htmlspecialchars($row['reason']) ?></span>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Không có ghi chú</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
