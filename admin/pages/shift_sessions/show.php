<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="font-serif fw-bold text-dark mb-1">
            <a href="<?= e(admin_url('shift_sessions')) ?>" class="text-muted me-2"><i class="fa-solid fa-arrow-left"></i></a>
            Biên bản bàn giao ca #<?= e($session['session_code']) ?>
        </h4>
        <p class="text-muted small mb-0">
            Người phụ trách: <strong><?= e($session['user_name']) ?></strong> 
            | Mở lúc: <strong><?= date('d/m/Y H:i', strtotime($session['opened_at'])) ?></strong>
            | Đóng lúc: <strong><?= !empty($session['closed_at']) ? date('d/m/Y H:i', strtotime($session['closed_at'])) : 'Đang hoạt động' ?></strong>
        </p>
    </div>
    <div>
        <button class="btn btn-outline-forest rounded-pill px-4" onclick="window.print()">
            <i class="fa-solid fa-print me-2"></i> In biên bản
        </button>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
            <span class="text-muted small">Tiền két đầu ca</span>
            <h5 class="fw-bold text-dark font-monospace mb-0"><?= money($session['initial_cash']) ?></h5>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
            <span class="text-muted small">Tổng doanh thu ca</span>
            <h5 class="fw-bold text-forest font-monospace mb-0"><?= money($session['total_sales']) ?></h5>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
            <span class="text-muted small">Tiền mặt thực tế đếm</span>
            <h5 class="fw-bold text-dark font-monospace mb-0"><?= money($session['actual_cash']) ?></h5>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
            <span class="text-muted small">Chênh lệch tiền két</span>
            <?php $diff = (float)$session['cash_difference']; ?>
            <h5 class="fw-bold font-monospace mb-0 <?= $diff < 0 ? 'text-danger' : ($diff > 0 ? 'text-warning' : 'text-success') ?>">
                <?= $diff < 0 ? 'Thiếu ' . money(abs($diff)) : ($diff > 0 ? 'Thừa +' . money($diff) : 'Khớp (0đ)') ?>
            </h5>
        </div>
    </div>
</div>

<!-- Bảng đơn hàng trong ca -->
<div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
    <div class="card-header bg-white border-0 py-3 px-4">
        <h5 class="fw-bold text-dark mb-0 font-serif text-forest">Đơn hàng trong phiên ca (<?= count($orders) ?> đơn)</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-4 py-3 fw-medium border-0">Mã đơn</th>
                        <th class="py-3 fw-medium border-0">Thời gian</th>
                        <th class="py-3 fw-medium border-0">PTTT</th>
                        <th class="py-3 fw-medium border-0">Tổng tiền</th>
                        <th class="pe-4 py-3 fw-medium border-0 text-end">Trạng thái</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <?php if (empty($orders)): ?>
                        <tr><td colspan="5" class="text-center py-4 text-muted">Không có đơn hàng nào trong ca này.</td></tr>
                    <?php else: ?>
                        <?php foreach ($orders as $ord): ?>
                        <tr>
                            <td class="ps-4 fw-bold font-monospace">
                                <a href="<?= e(admin_url('order-detail', ['id' => $ord['id']])) ?>" class="text-forest text-decoration-none">
                                    #<?= e($ord['order_number'] ?? $ord['id']) ?>
                                </a>
                            </td>
                            <td class="small text-muted"><?= date('H:i:s d/m/Y', strtotime($ord['created_at'])) ?></td>
                            <td><span class="badge bg-light text-dark border"><?= e($ord['payment_method']) ?></span></td>
                            <td class="fw-bold text-forest"><?= money($ord['final_amount']) ?></td>
                            <td class="text-end pe-4">
                                <span class="badge bg-secondary bg-opacity-10 text-dark rounded-pill px-3"><?= e($ord['status']) ?></span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
