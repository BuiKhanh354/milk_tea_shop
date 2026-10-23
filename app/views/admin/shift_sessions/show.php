<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #printArea, #printArea * {
            visibility: visible;
        }
        #printArea {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            padding: 20px;
        }
        .no-print {
            display: none !important;
        }
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4 no-print">
    <div>
        <h4 class="font-serif fw-bold text-dark mb-1">
            <a href="admin.php?route=shift_sessions" class="text-muted me-2"><i class="fa-solid fa-arrow-left"></i></a>
            Biên bản bàn giao Ca #<?= htmlspecialchars($session['session_code']) ?>
        </h4>
        <p class="text-muted small mb-0">Thu ngân: <strong><?= htmlspecialchars($session['user_name']) ?></strong> | Trạng thái: <?= $session['status'] === 'closed' ? '<span class="text-success fw-bold">Đã kết ca</span>' : '<span class="text-warning fw-bold">Đang mở</span>' ?></p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-light border rounded-pill px-3" onclick="window.print()">
            <i class="fa-solid fa-print me-1"></i> In biên bản
        </button>
        <a href="admin.php?route=shift_sessions&action=open" class="btn btn-forest rounded-pill px-3">
            <i class="fa-solid fa-plus me-1"></i> Mở ca mới
        </a>
    </div>
</div>

<div id="printArea">
    <!-- Header phiếu in -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="text-center mb-4 pb-3 border-bottom">
                <h4 class="font-serif fw-bold text-dark mb-1">VAA THÉ - BIÊN BẢN BÀN GIAO CA LÀM VIỆC</h4>
                <p class="text-muted small mb-1">Mã phiên: <strong class="font-monospace"><?= htmlspecialchars($session['session_code']) ?></strong></p>
                <div class="small text-muted">
                    Giờ mở: <?= date('d/m/Y H:i:s', strtotime($session['opened_at'])) ?> 
                    <?php if (!empty($session['closed_at'])): ?>
                        | Giờ đóng: <?= date('d/m/Y H:i:s', strtotime($session['closed_at'])) ?>
                    <?php endif; ?>
                    | Nhân viên: <strong class="text-dark"><?= htmlspecialchars($session['user_name']) ?></strong>
                </div>
            </div>

            <!-- 1. TỔNG KẾT TIỀN & DOANH THU -->
            <h6 class="fw-bold text-dark mb-3 text-uppercase">1. Báo cáo Doanh thu & Tiền mặt két</h6>
            <div class="row g-3 mb-4">
                <div class="col-sm-3">
                    <div class="p-3 bg-light rounded-3 border">
                        <span class="text-muted small d-block">Tiền mặt ban đầu</span>
                        <h5 class="fw-bold text-dark mb-0 font-monospace"><?= number_format($session['initial_cash'], 0, ',', '.') ?>đ</h5>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="p-3 bg-light rounded-3 border">
                        <span class="text-muted small d-block">Tiền mặt bán được</span>
                        <h5 class="fw-bold text-forest mb-0 font-monospace">+<?= number_format($session['cash_sales'], 0, ',', '.') ?>đ</h5>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="p-3 bg-light rounded-3 border">
                        <span class="text-muted small d-block">Chuyển khoản & MoMo</span>
                        <h5 class="fw-bold text-primary mb-0 font-monospace"><?= number_format($session['bank_sales'] + $session['momo_sales'], 0, ',', '.') ?>đ</h5>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="p-3 bg-forest bg-opacity-10 rounded-3 border border-forest">
                        <span class="text-light small d-block fw-bold">TỔNG DOANH THU BÁN</span>
                        <h5 class="fw-bold text-light mb-0 font-monospace"><?= number_format($session['total_sales'], 0, ',', '.') ?>đ</h5>
                    </div>
                </div>
            </div>

            <!-- Đối chiếu két tiền mặt -->
            <div class="p-3 bg-light rounded-3 border mb-4">
                <div class="row align-items-center">
                    <div class="col-md-4 mb-2 mb-md-0">
                        <span class="text-muted small d-block">Tiền mặt lý thuyết cần có:</span>
                        <strong class="font-monospace fs-6"><?= number_format($session['expected_cash'], 0, ',', '.') ?>đ</strong>
                    </div>
                    <div class="col-md-4 mb-2 mb-md-0">
                        <span class="text-muted small d-block">Tiền mặt thực tế đếm được:</span>
                        <strong class="font-monospace fs-6 text-primary"><?= number_format($session['actual_cash'] ?? $session['expected_cash'], 0, ',', '.') ?>đ</strong>
                    </div>
                    <div class="col-md-4">
                        <span class="text-muted small d-block">Chênh lệch két tiền mặt:</span>
                        <?php 
                            $diff = (float)($session['cash_difference'] ?? 0);
                        ?>
                        <?php if (abs($diff) < 1): ?>
                            <span class="badge bg-success rounded-pill px-3">Khớp 100% (0đ)</span>
                        <?php elseif ($diff < 0): ?>
                            <span class="badge bg-danger rounded-pill px-3 font-monospace">Thiếu <?= number_format($diff, 0, ',', '.') ?>đ</span>
                        <?php else: ?>
                            <span class="badge bg-warning text-dark rounded-pill px-3 font-monospace">Thừa +<?= number_format($diff, 0, ',', '.') ?>đ</span>
                        <?php endif; ?>
                        <?php if (!empty($session['cash_difference_reason'])): ?>
                            <small class="d-block text-danger mt-1">Lý do: <?= htmlspecialchars($session['cash_difference_reason']) ?></small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- 2. BÀN GIAO NGUYÊN LIỆU -->
            <h6 class="fw-bold text-dark mb-3 text-uppercase">2. Đối chiếu & Bàn giao Nguyên liệu tại quầy</h6>
            <div class="table-responsive mb-4">
                <table class="table align-middle table-bordered small">
                    <thead class="bg-light text-muted">
                        <tr>
                            <th class="py-2" style="width: 5%;">#</th>
                            <th class="py-2" style="width: 25%;">Tên nguyên liệu</th>
                            <th class="py-2 text-center" style="width: 8%;">ĐVT</th>
                            <th class="py-2 text-end" style="width: 12%;">Tồn đầu ca</th>
                            <th class="py-2 text-end" style="width: 12%;">Dự kiến dùng (Đơn)</th>
                            <th class="py-2 text-end" style="width: 12%;">Tồn lý thuyết</th>
                            <th class="py-2 text-end" style="width: 12%;">Thực tế bàn giao</th>
                            <th class="py-2 text-center" style="width: 14%;">Lệch / Ghi chú</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($inventoryDetails as $idx => $inv): 
                            $diffQty = (float)($inv['difference_qty'] ?? 0);
                        ?>
                        <tr>
                            <td class="text-center text-muted"><?= $idx + 1 ?></td>
                            <td class="fw-bold text-dark"><?= htmlspecialchars($inv['ingredient_name']) ?></td>
                            <td class="text-center"><?= htmlspecialchars($inv['unit']) ?></td>
                            <td class="text-end font-monospace"><?= number_format($inv['start_quantity'], 2, '.', '') ?></td>
                            <td class="text-end font-monospace text-forest"><?= number_format($inv['expected_usage'], 2, '.', '') ?></td>
                            <td class="text-end font-monospace"><?= number_format($inv['expected_ending_qty'], 2, '.', '') ?></td>
                            <td class="text-end font-monospace fw-bold text-primary"><?= number_format($inv['actual_ending_qty'] ?? $inv['expected_ending_qty'], 2, '.', '') ?></td>
                            <td class="text-center small">
                                <?php if (abs($diffQty) < 0.001): ?>
                                    <span class="text-success fw-bold">Khớp</span>
                                <?php elseif ($diffQty < 0): ?>
                                    <span class="text-danger fw-bold">Hụt <?= number_format($diffQty, 2, '.', '') ?></span>
                                <?php else: ?>
                                    <span class="text-warning text-dark fw-bold">+<?= number_format($diffQty, 2, '.', '') ?></span>
                                <?php endif; ?>
                                <?php if (!empty($inv['reason'])): ?>
                                    <span class="d-block text-muted">(<?= htmlspecialchars($inv['reason']) ?>)</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Ghi chú chung -->
            <?php if (!empty($session['note'])): ?>
            <div class="mb-4 p-3 bg-light rounded-3 small">
                <strong>Ghi chú ca:</strong> <?= htmlspecialchars($session['note']) ?>
            </div>
            <?php endif; ?>

            <!-- Ký nhận bàn giao -->
            <div class="row text-center mt-5 pt-3">
                <div class="col-6">
                    <span class="fw-bold text-dark d-block">NGƯỜI BÀN GIAO (RA CA)</span>
                    <small class="text-muted">(Ký và ghi rõ họ tên)</small>
                    <div style="height: 70px;"></div>
                    <span class="fw-bold text-dark"><?= htmlspecialchars($session['user_name']) ?></span>
                </div>
                <div class="col-6">
                    <span class="fw-bold text-dark d-block">NGƯỜI NHẬN BÀN GIAO / QUẢN LÝ</span>
                    <small class="text-muted">(Ký và ghi rõ họ tên)</small>
                    <div style="height: 70px;"></div>
                    <span class="text-muted">...................................................</span>
                </div>
            </div>
        </div>
    </div>
</div>
