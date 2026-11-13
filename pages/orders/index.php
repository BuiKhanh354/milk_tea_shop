<!-- pages/orders/index.php — Lịch sử đơn hàng -->
<section class="orders-section py-5">
    <div class="container">
        
        <h1 class="font-serif display-5 fw-bold text-forest mb-4">Lịch Sử Đơn Hàng</h1>

        <?php if (!empty($orders)): ?>
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="text-muted small border-bottom">
                            <tr>
                                <th>Mã đơn</th>
                                <th>Ngày đặt</th>
                                <th>Tổng tiền</th>
                                <th>Hình thức</th>
                                <th>Trạng thái</th>
                                <th>Ghi chú</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $ord): ?>
                            <tr>
                                <td class="fw-bold text-forest">#<?= (int)$ord['id'] ?></td>
                                <td class="text-muted small"><?= date('d/m/Y H:i', strtotime($ord['created_at'])) ?></td>
                                <td class="fw-bold text-caramel"><?= money($ord['final_amount']) ?></td>
                                <td><span class="badge bg-light text-dark"><?= e($ord['order_type']) ?></span></td>
                                <td>
                                    <?php if ($ord['status'] === 'completed'): ?>
                                        <span class="badge bg-success">Hoàn thành</span>
                                    <?php elseif ($ord['status'] === 'cancelled'): ?>
                                        <span class="badge bg-danger">Đã huỷ</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark"><?= e($ord['status']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="small text-muted"><?= e($ord['note'] ?? '-') ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fa-solid fa-receipt fa-4x text-muted mb-3 opacity-50"></i>
                <h4 class="font-serif text-forest mb-2">Bạn chưa có đơn hàng nào</h4>
                <p class="text-muted mb-4">Các đơn hàng bạn đã đặt sẽ xuất hiện tại đây.</p>
                <a href="<?= e(url('products')) ?>" class="btn btn-forest px-4 py-2 rounded-pill fw-semibold">Đặt Trà Sữa Ngay</a>
            </div>
        <?php endif; ?>

    </div>
</section>

