<?php
// admin/pages/shift_sessions/show.php — Chi tiết biên bản bàn giao ca làm việc chuẩn gốc VAA THÉ
?>
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

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2 no-print">
    <div>
        <h4 class="font-serif fw-bold text-dark mb-1">
            <a href="<?= e(admin_url('shift_sessions')) ?>" class="text-muted me-2"><i class="fa-solid fa-arrow-left"></i></a>
            Biên bản bàn giao Ca #<?= e($session['session_code']) ?>
        </h4>
        <p class="text-muted small mb-0">
            Người phụ trách: <strong><?= e($session['user_name']) ?></strong> 
            | Trạng thái: <?= $session['status'] === 'closed' ? '<span class="text-success fw-bold">Đã kết ca</span>' : '<span class="text-warning fw-bold">Đang mở</span>' ?>
            | Mở lúc: <strong><?= date('d/m/Y H:i', strtotime($session['opened_at'])) ?></strong>
            <?php if (!empty($session['closed_at'])): ?>
                | Đóng lúc: <strong><?= date('d/m/Y H:i', strtotime($session['closed_at'])) ?></strong>
            <?php endif; ?>
        </p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-outline-forest rounded-pill px-4" onclick="window.print()">
            <i class="fa-solid fa-print me-2"></i> In biên bản
        </button>
        <?php if ($session['status'] === 'open'): ?>
            <a href="<?= e(admin_url('shift_sessions', ['action' => 'close'])) ?>" class="btn btn-forest rounded-pill px-4">
                <i class="fa-solid fa-cash-register me-2"></i> Kết ca ngay
            </a>
        <?php else: ?>
            <a href="<?= e(admin_url('shift_sessions', ['action' => 'open'])) ?>" class="btn btn-forest rounded-pill px-4">
                <i class="fa-solid fa-plus me-2"></i> Mở ca mới
            </a>
        <?php endif; ?>
    </div>
</div>

<div id="printArea">
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <!-- Header phiếu in -->
            <div class="text-center mb-4 pb-3 border-bottom">
                <h4 class="font-serif fw-bold text-forest mb-1">VAA THÉ · BIÊN BẢN BÀN GIAO CA LÀM VIỆC</h4>
                <p class="text-muted small mb-1">Mã phiên ca: <strong class="font-monospace text-dark"><?= e($session['session_code']) ?></strong></p>
                <div class="small text-muted">
                    Giờ mở ca: <strong><?= date('d/m/Y H:i:s', strtotime($session['opened_at'])) ?></strong> 
                    <?php if (!empty($session['closed_at'])): ?>
                        | Giờ đóng ca: <strong><?= date('d/m/Y H:i:s', strtotime($session['closed_at'])) ?></strong>
                    <?php endif; ?>
                    | Nhân viên: <strong class="text-dark"><?= e($session['user_name']) ?></strong>
                    <?php if (!empty($session['shift_name'])): ?>
                        | Ca: <span class="badge bg-secondary"><?= e($session['shift_name']) ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- 1. BÁO CÁO DOANH THU & TIỀN MẶT KÉT -->
            <h6 class="fw-bold text-dark mb-3 text-uppercase font-serif">1. Báo cáo Doanh thu & Tiền mặt két</h6>
            <div class="row g-3 mb-3">
                <div class="col-sm-3">
                    <div class="p-3 bg-light rounded-4 border-0">
                        <span class="text-muted small d-block mb-1">Tiền két ban đầu</span>
                        <h5 class="fw-bold text-dark mb-0 font-monospace"><?= money($session['initial_cash']) ?></h5>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="p-3 bg-light rounded-4 border-0">
                        <span class="text-muted small d-block mb-1">Tiền mặt bán được</span>
                        <h5 class="fw-bold text-forest mb-0 font-monospace">+<?= money($session['cash_sales'] ?? 0) ?></h5>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="p-3 bg-light rounded-4 border-0">
                        <span class="text-muted small d-block mb-1">Chuyển khoản & MoMo</span>
                        <h5 class="fw-bold text-primary mb-0 font-monospace"><?= money(($session['bank_sales'] ?? 0) + ($session['momo_sales'] ?? 0)) ?></h5>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="p-3 bg-forest bg-opacity-10 rounded-4 border-0">
                        <span class="text-forest small d-block fw-bold mb-1">TỔNG DOANH THU CA</span>
                        <h5 class="fw-bold text-forest mb-0 font-monospace"><?= money($session['total_sales'] ?? 0) ?></h5>
                    </div>
                </div>
            </div>

            <!-- Đối chiếu két tiền mặt -->
            <div class="p-3 bg-light rounded-4 mb-4">
                <div class="row align-items-center">
                    <div class="col-md-4 mb-2 mb-md-0">
                        <span class="text-muted small d-block">Tiền mặt lý thuyết trong két:</span>
                        <strong class="font-monospace fs-6 text-dark"><?= money($session['expected_cash']) ?></strong>
                    </div>
                    <div class="col-md-4 mb-2 mb-md-0">
                        <span class="text-muted small d-block">Tiền mặt thực tế kiểm đếm:</span>
                        <strong class="font-monospace fs-6 text-dark"><?= money($session['actual_cash']) ?></strong>
                    </div>
                    <div class="col-md-4">
                        <span class="text-muted small d-block">Chênh lệch tiền két:</span>
                        <?php $diff = (float)($session['cash_difference'] ?? 0); ?>
                        <?php if (abs($diff) < 0.01): ?>
                            <span class="badge bg-success rounded-pill px-3 py-2 fw-bold">Khớp két (0đ)</span>
                        <?php elseif ($diff < 0): ?>
                            <span class="badge bg-danger rounded-pill px-3 py-2 fw-bold">Thiếu <?= money(abs($diff)) ?></span>
                        <?php else: ?>
                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2 fw-bold">Thừa +<?= money($diff) ?></span>
                        <?php endif; ?>
                        <?php if (!empty($session['cash_difference_reason'])): ?>
                            <small class="d-block text-danger mt-1">Lý do: <?= e($session['cash_difference_reason']) ?></small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- 2. BÀN GIAO NGUYÊN LIỆU TẠI QUẦY -->
            <h6 class="fw-bold text-dark mb-3 text-uppercase font-serif">2. Đối chiếu & Bàn giao Nguyên liệu tại quầy</h6>
            <div class="table-responsive mb-4">
                <table class="table align-middle table-hover mb-0">
                    <thead class="bg-light text-muted small">
                        <tr>
                            <th class="ps-3 py-2" style="width: 5%;">#</th>
                            <th class="py-2" style="width: 25%;">Tên nguyên liệu</th>
                            <th class="py-2 text-center" style="width: 8%;">ĐVT</th>
                            <th class="py-2 text-end" style="width: 12%;">Tồn đầu ca</th>
                            <th class="py-2 text-end" style="width: 12%;">Dự kiến dùng (Đơn)</th>
                            <th class="py-2 text-end" style="width: 12%;">Tồn lý thuyết</th>
                            <th class="py-2 text-end" style="width: 12%;">Thực tế bàn giao</th>
                            <th class="pe-3 py-2 text-center" style="width: 14%;">Lệch / Ghi chú</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        <?php if (empty($inventoryDetails)): ?>
                            <tr><td colspan="8" class="text-center py-4 text-muted">Không có dữ liệu nguyên liệu bàn giao trong ca này.</td></tr>
                        <?php else: ?>
                            <?php foreach ($inventoryDetails as $idx => $inv): 
                                $diffQty = (float)($inv['difference_qty'] ?? 0);
                            ?>
                            <tr>
                                <td class="ps-3 text-muted"><?= $idx + 1 ?></td>
                                <td class="fw-bold text-dark"><?= e($inv['ingredient_name']) ?></td>
                                <td class="text-center"><span class="badge bg-light text-dark border"><?= e($inv['unit']) ?></span></td>
                                <td class="text-end font-monospace"><?= number_format((float)$inv['start_quantity'], 2) ?></td>
                                <td class="text-end font-monospace text-forest"><?= number_format((float)$inv['expected_usage'], 2) ?></td>
                                <td class="text-end font-monospace"><?= number_format((float)$inv['expected_ending_qty'], 2) ?></td>
                                <td class="text-end font-monospace fw-bold text-primary"><?= number_format((float)($inv['actual_ending_qty'] ?? $inv['expected_ending_qty']), 2) ?></td>
                                <td class="pe-3 text-center small">
                                    <?php if (abs($diffQty) < 0.001): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2">Khớp</span>
                                    <?php elseif ($diffQty < 0): ?>
                                        <span class="badge bg-danger rounded-pill px-2">Hụt <?= number_format($diffQty, 2) ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark rounded-pill px-2">+<?= number_format($diffQty, 2) ?></span>
                                    <?php endif; ?>
                                    <?php if (!empty($inv['difference_reason'] ?? $inv['reason'])): ?>
                                        <div class="text-muted small mt-1">(<?= e($inv['difference_reason'] ?? $inv['reason']) ?>)</div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- 3. ĐƠN HÀNG TRONG PHIÊN CA -->
            <h6 class="fw-bold text-dark mb-3 text-uppercase font-serif">3. Danh sách đơn hàng trong ca (<?= count($orders) ?> đơn)</h6>
            <div class="table-responsive mb-4">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small">
                        <tr>
                            <th class="ps-3 py-2">Mã đơn</th>
                            <th class="py-2">Thời gian</th>
                            <th class="py-2">Khách hàng</th>
                            <th class="py-2">Loại đơn</th>
                            <th class="py-2">PTTT</th>
                            <th class="py-2 text-end">Tổng tiền</th>
                            <th class="pe-3 py-2 text-end">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        <?php if (empty($orders)): ?>
                            <tr><td colspan="7" class="text-center py-4 text-muted">Không có đơn hàng nào phát sinh trong ca làm việc này.</td></tr>
                        <?php else: ?>
                            <?php foreach ($orders as $ord): ?>
                            <tr>
                                <td class="ps-3 fw-bold font-monospace">
                                    <a href="<?= e(admin_url('order-detail', ['id' => $ord['id']])) ?>" class="text-forest text-decoration-none">
                                        #ORD<?= str_pad($ord['id'], 3, '0', STR_PAD_LEFT) ?>
                                    </a>
                                </td>
                                <td class="small text-muted"><?= date('H:i:s d/m/Y', strtotime($ord['created_at'])) ?></td>
                                <td class="small text-dark fw-medium"><?= e($ord['customer_name'] ?? 'Khách lẻ') ?></td>
                                <td class="small"><span class="badge bg-light text-dark border"><?= e(ucfirst(str_replace('_', ' ', $ord['order_type'] ?? ''))) ?></span></td>
                                <td><span class="badge bg-light text-dark border"><?= e(format_payment_method($ord['payment_method'])) ?></span></td>
                                <td class="fw-bold text-forest text-end"><?= money($ord['final_amount']) ?></td>
                                <td class="text-end pe-3">
                                    <?php 
                                    $sttClass = 'bg-secondary bg-opacity-10 text-secondary';
                                    if ($ord['status'] === 'completed') $sttClass = 'bg-success bg-opacity-10 text-success';
                                    elseif ($ord['status'] === 'pending') $sttClass = 'bg-warning bg-opacity-10 text-warning';
                                    elseif ($ord['status'] === 'preparing') $sttClass = 'bg-primary bg-opacity-10 text-primary';
                                    ?>
                                    <span class="badge <?= $sttClass ?> rounded-pill px-3"><?= ucfirst($ord['status']) ?></span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Ghi chú chung -->
            <?php if (!empty($session['note'])): ?>
            <div class="mb-4 p-3 bg-light rounded-4 small">
                <strong><i class="fa-solid fa-note-sticky me-1 text-caramel"></i> Ghi chú ca:</strong> <?= e($session['note']) ?>
            </div>
            <?php endif; ?>

            <!-- Ký nhận bàn giao -->
            <div class="row text-center mt-5 pt-3">
                <div class="col-6">
                    <span class="fw-bold text-dark d-block">NGƯỜI BÀN GIAO (RA CA)</span>
                    <small class="text-muted">(Ký và ghi rõ họ tên)</small>
                    <div style="height: 70px;"></div>
                    <span class="fw-bold text-dark"><?= e($session['user_name']) ?></span>
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
