<!-- admin/pages/dashboard/index.php -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <span class="text-muted small text-uppercase fw-bold">Tổng Doanh Thu</span>
            <h3 class="font-serif fw-bold text-caramel mt-2 mb-0"><?= money($totalRevenue) ?></h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <span class="text-muted small text-uppercase fw-bold">Đơn Hàng</span>
            <h3 class="font-serif fw-bold text-forest mt-2 mb-0"><?= number_format($totalOrders) ?></h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <span class="text-muted small text-uppercase fw-bold">Sản Phẩm</span>
            <h3 class="font-serif fw-bold text-forest mt-2 mb-0"><?= number_format($totalProducts) ?></h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <span class="text-muted small text-uppercase fw-bold">Khách Hàng</span>
            <h3 class="font-serif fw-bold text-forest mt-2 mb-0"><?= number_format($totalCustomers) ?></h3>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="font-serif fw-bold text-forest mb-0">Đơn Hàng Mới Nhất</h5>
        <a href="<?= e(admin_url('orders')) ?>" class="btn btn-sm btn-outline-forest rounded-pill">Xem tất cả</a>
    </div>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead class="text-muted small border-bottom">
                <tr>
                    <th>Mã Đơn</th>
                    <th>Khách Hàng</th>
                    <th>Thời Gian</th>
                    <th>Tổng Tiền</th>
                    <th>Trạng Thái</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentOrders as $ro): ?>
                <tr>
                    <td class="fw-bold">#<?= (int)$ro['id'] ?></td>
                    <td><?= e($ro['customer_name'] ?? 'Khách vãng lai') ?></td>
                    <td class="text-muted small"><?= date('d/m/Y H:i', strtotime($ro['created_at'])) ?></td>
                    <td class="fw-bold text-caramel"><?= money($ro['final_amount']) ?></td>
                    <td><span class="badge bg-warning text-dark"><?= e($ro['status']) ?></span></td>
                    <td>
                        <a href="<?= e(admin_url('order-detail', ['id' => $ro['id']])) ?>" class="btn btn-sm btn-light">Chi tiết</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

