<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="font-serif fw-bold text-dark mb-1">Quản lý thanh toán & Giao dịch</h4>
        <p class="text-muted small mb-0">Theo dõi dòng tiền, phương thức thanh toán và trạng thái đối soát giao dịch</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= e(admin_url('orders')) ?>" class="btn btn-outline-forest rounded-pill fw-medium px-3">
            <i class="fa-solid fa-receipt me-2"></i> Xem đơn hàng
        </a>
        <a href="<?= e(admin_url('shift_sessions')) ?>" class="btn btn-forest rounded-pill fw-medium px-3">
            <i class="fa-solid fa-cash-register me-2"></i> Bàn giao ca
        </a>
    </div>
</div>

<?php if ($successMsg = get_flash('success')): ?>
    <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4">
        <i class="fa-solid fa-circle-check me-2"></i> <?= e($successMsg) ?>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- 4 Thẻ KPI thống kê thanh toán -->
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
            <span class="text-muted small fw-medium">Tổng thực thu (Đã thanh toán)</span>
            <h4 class="fw-bold text-success mb-1 mt-1"><?= money($stats['total_paid_amount']) ?></h4>
            <div class="text-muted small"><?= $stats['total_transactions'] ?> giao dịch</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
            <span class="text-muted small fw-medium">Tiền mặt tại quầy</span>
            <h4 class="fw-bold text-forest mb-1 mt-1"><?= money($stats['cash_paid_amount']) ?></h4>
            <div class="text-muted small">Thu ngân bàn giao két</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
            <span class="text-muted small fw-medium">Chuyển khoản & Ví điện tử</span>
            <h4 class="fw-bold text-primary mb-1 mt-1"><?= money($stats['online_paid_amount']) ?></h4>
            <div class="text-muted small">Ngân hàng & Ví MoMo</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
            <span class="text-muted small fw-medium">Chờ thanh toán (Pending)</span>
            <h4 class="fw-bold text-warning mb-1 mt-1"><?= money($stats['pending_amount']) ?></h4>
            <div class="text-muted small">Chờ khách xác nhận</div>
        </div>
    </div>
</div>

<!-- Bảng thanh toán -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-4 py-3 fw-medium border-0">Mã GD</th>
                        <th class="py-3 fw-medium border-0">Đơn hàng</th>
                        <th class="py-3 fw-medium border-0">Khách hàng</th>
                        <th class="py-3 fw-medium border-0">Phương thức</th>
                        <th class="py-3 fw-medium border-0 text-end">Số tiền</th>
                        <th class="py-3 fw-medium border-0 text-center">Trạng thái</th>
                        <th class="py-3 fw-medium border-0">Thời gian</th>
                        <th class="pe-4 py-3 fw-medium border-0 text-end">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <?php if (empty($payments)): ?>
                        <tr><td colspan="8" class="text-center py-5 text-muted">Chưa có giao dịch thanh toán nào.</td></tr>
                    <?php else: ?>
                        <?php foreach ($payments as $p): ?>
                        <tr>
                            <td class="ps-4 fw-bold font-monospace text-dark">
                                <?= e($p['transaction_code'] ?: ('TXN' . str_pad($p['id'], 5, '0', STR_PAD_LEFT))) ?>
                            </td>
                            <td>
                                <a href="<?= e(admin_url('order-detail', ['id' => $p['order_id']])) ?>" class="fw-bold text-forest text-decoration-none">
                                    #<?= e($p['order_number'] ?: $p['order_id']) ?>
                                </a>
                            </td>
                            <td><?= e($p['customer_name'] ?? 'Khách vãng lai') ?></td>
                            <td><span class="badge bg-light text-dark border px-2 py-1"><?= e($p['method']) ?></span></td>
                            <td class="text-end fw-bold text-forest font-monospace"><?= money($p['amount']) ?></td>
                            <td class="text-center">
                                <?php if ($p['status'] === 'paid'): ?>
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Đã thanh toán</span>
                                <?php elseif ($p['status'] === 'pending'): ?>
                                    <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3">Chờ thanh toán</span>
                                <?php else: ?>
                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3"><?= e($p['status']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="small text-muted"><?= date('H:i d/m/Y', strtotime($p['created_at'])) ?></td>
                            <td class="text-end pe-4">
                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#editPayModal<?= (int)$p['id'] ?>">
                                    Cập nhật
                                </button>
                            </td>
                        </tr>

                        <!-- Modal Cập nhật thanh toán -->
                        <div class="modal fade" id="editPayModal<?= (int)$p['id'] ?>" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 rounded-4 shadow">
                                    <form action="<?= e(admin_url('payments', ['action' => 'update_status'])) ?>" method="POST">
                                        <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                                        <div class="modal-header border-bottom-0 pb-0 px-4 pt-4">
                                            <h5 class="modal-title font-serif fw-bold text-forest">Cập nhật thanh toán</h5>
                                            <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body px-4 py-3">
                                            <div class="mb-3">
                                                <label class="form-label text-muted small fw-medium mb-1">Trạng thái thanh toán</label>
                                                <select name="status" class="form-select border-secondary border-opacity-25 shadow-none" required>
                                                    <option value="paid" <?= $p['status'] === 'paid' ? 'selected' : '' ?>>Đã thanh toán (Paid)</option>
                                                    <option value="pending" <?= $p['status'] === 'pending' ? 'selected' : '' ?>>Chờ thanh toán (Pending)</option>
                                                    <option value="failed" <?= $p['status'] === 'failed' ? 'selected' : '' ?>>Thất bại (Failed)</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label text-muted small fw-medium mb-1">Mã tham chiếu / giao dịch</label>
                                                <input type="text" name="transaction_code" class="form-control border-secondary border-opacity-25 shadow-none" value="<?= e($p['transaction_code'] ?? '') ?>" placeholder="VD: MOMO123456...">
                                            </div>
                                        </div>
                                        <div class="modal-footer border-top-0 px-4 pb-4 pt-0">
                                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Đóng</button>
                                            <button type="submit" class="btn btn-forest rounded-pill px-4">Lưu thay đổi</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
