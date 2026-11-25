<div class="mb-4">
    <h3 class="font-serif fw-bold text-forest mb-1">Xin chào, <?= e($_SESSION['full_name'] ?? 'Administrator') ?>!</h3>
    <p class="text-muted">Hôm nay bạn có thể theo dõi toàn bộ hoạt động của VAA THÉ.</p>
</div>

<!-- 4 Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-12 col-md-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon bg-success bg-opacity-10 text-success">
                    <i class="fa-solid fa-wallet"></i>
                </div>
                <span class="badge bg-success bg-opacity-10 text-success rounded-pill fw-medium px-2 py-1"><?= e($stats['revenue']['growth']) ?></span>
            </div>
            <h6 class="text-muted fw-semibold mb-1">Doanh thu hôm nay</h6>
            <h3 class="fw-bold mb-0 text-dark"><?= e($stats['revenue']['value']) ?></h3>
        </div>
    </div>
    
    <div class="col-12 col-md-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon bg-info bg-opacity-10 text-info">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <span class="badge bg-success bg-opacity-10 text-success rounded-pill fw-medium px-2 py-1"><?= e($stats['orders']['growth']) ?></span>
            </div>
            <h6 class="text-muted fw-semibold mb-1">Đơn hàng hôm nay</h6>
            <h3 class="fw-bold mb-0 text-dark"><?= e($stats['orders']['value']) ?></h3>
        </div>
    </div>
    
    <div class="col-12 col-md-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="fa-solid fa-users"></i>
                </div>
                <span class="badge bg-success bg-opacity-10 text-success rounded-pill fw-medium px-2 py-1"><?= e($stats['customers']['growth']) ?></span>
            </div>
            <h6 class="text-muted fw-semibold mb-1">Khách hàng</h6>
            <h3 class="fw-bold mb-0 text-dark"><?= e($stats['customers']['value']) ?></h3>
        </div>
    </div>
    
    <div class="col-12 col-md-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="fa-solid"></i>
                </div>
                <span class="badge bg-success bg-opacity-10 text-success rounded-pill fw-medium px-2 py-1"><?= e($stats['products']['growth']) ?></span>
            </div>
            <h6 class="text-muted fw-semibold mb-1">Sản phẩm đang bán</h6>
            <h3 class="fw-bold mb-0 text-dark"><?= e($stats['products']['value']) ?></h3>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Chart -->
    <div class="col-12 col-xl-8">
        <div class="stat-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold text-dark mb-0">Biểu đồ doanh thu</h5>
                <select class="form-select form-select-sm w-auto shadow-none">
                    <option value="7">7 ngày qua</option>
                    <option value="30">30 ngày qua</option>
                    <option value="12">12 tháng qua</option>
                </select>
            </div>
            <div class="revenue-chart-container" style="position: relative; height: 300px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Cảnh báo kho -->
    <div class="col-12 col-xl-4">
        <div class="stat-card h-100 d-flex flex-column">
            <h5 class="fw-bold text-dark mb-4">Cảnh báo kho</h5>
            <div class="flex-grow-1 overflow-auto">
                <table class="table table-borderless table-sm mb-0">
                    <thead class="text-muted">
                        <tr>
                            <th>Nguyên liệu</th>
                            <th>Tồn kho</th>
                            <th>Tối thiểu</th>
                            <th class="text-end">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody class="align-middle">
                        <?php foreach($inventoryAlerts as $alert): ?>
                        <tr>
                            <td class="fw-medium text-dark"><?= e($alert['name']) ?></td>
                            <td><?= e($alert['stock']) ?></td>
                            <td class="text-muted"><?= e($alert['min']) ?></td>
                            <td class="text-end">
                                <span class="badge <?= $alert['status'] === 'Hết hàng' ? 'bg-danger' : 'bg-warning text-dark' ?> rounded-1">
                                    <?= e($alert['status']) ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Đơn hàng gần đây -->
<div class="admin-table-card mb-4 p-3 bg-white rounded-4 shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-3 px-2">
        <h5 class="fw-bold text-dark mb-0">Đơn hàng gần đây</h5>
        <a href="<?= e(admin_url('orders')) ?>" class="btn btn-sm btn-outline-forest rounded-pill px-3">Xem tất cả</a>
    </div>
    <div class="table-responsive">
        <table class="table mb-0 text-nowrap align-middle">
            <thead class="bg-light text-muted">
                <tr>
                    <th class="ps-3 py-2 fw-medium border-0">Mã đơn</th>
                    <th class="py-2 fw-medium border-0">Khách hàng</th>
                    <th class="py-2 fw-medium border-0">Loại đơn</th>
                    <th class="py-2 fw-medium border-0">Tổng tiền</th>
                    <th class="py-2 fw-medium border-0">Thanh toán</th>
                    <th class="py-2 fw-medium border-0">Trạng thái</th>
                    <th class="py-2 fw-medium border-0">Thời gian</th>
                    <th class="text-end pe-3 py-2 fw-medium border-0">Thao tác</th>
                </tr>
            </thead>
            <tbody class="border-top-0">
                <?php if (empty($recentOrders)): ?>
                    <tr><td colspan="8" class="text-center py-4 text-muted">Chưa có đơn hàng nào.</td></tr>
                <?php else: ?>
                    <?php foreach($recentOrders as $order): ?>
                    <tr>
                        <td class="ps-3 fw-bold text-forest font-monospace"><?= e($order['code']) ?></td>
                        <td><?= e($order['customer']) ?></td>
                        <td><span class="badge bg-light text-dark border"><?= e($order['type']) ?></span></td>
                        <td class="fw-bold text-dark font-monospace"><?= e($order['total']) ?></td>
                        <td><span class="badge bg-light text-dark border"><?= e($order['payment']) ?></span></td>
                        <td>
                            <?php 
                            $badgeClass = '';
                            $statusText = '';
                            switch(strtolower($order['status'])) {
                                case 'pending':   $badgeClass = 'bg-warning bg-opacity-25 text-dark'; $statusText = 'Chờ xác nhận'; break;
                                case 'confirmed': $badgeClass = 'bg-info bg-opacity-10 text-info'; $statusText = 'Đã xác nhận'; break;
                                case 'preparing': $badgeClass = 'bg-primary bg-opacity-10 text-primary'; $statusText = 'Đang chuẩn bị'; break;
                                case 'ready':     $badgeClass = 'bg-success bg-opacity-25 text-success'; $statusText = 'Sẵn sàng'; break;
                                case 'completed': $badgeClass = 'bg-success text-white'; $statusText = 'Hoàn thành'; break;
                                case 'cancelled': $badgeClass = 'bg-danger text-white'; $statusText = 'Đã hủy'; break;
                                default:          $badgeClass = 'bg-secondary text-white'; $statusText = $order['status'];
                            }
                            ?>
                            <span class="badge <?= $badgeClass ?> rounded-pill px-3 py-1"><?= $statusText ?></span>
                        </td>
                        <td class="text-muted small"><?= e($order['time']) ?></td>
                        <td class="text-end pe-3">
                            <a href="<?= e(admin_url('orders', ['action' => 'show', 'id' => $order['id']])) ?>" class="btn btn-sm btn-light border shadow-none" title="Xem chi tiết">
                                <i class="fa-solid fa-eye text-muted"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('revenueChart');
    if (ctx && typeof Chart !== 'undefined') {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'],
                datasets: [{
                    label: 'Doanh thu (Triệu VNĐ)',
                    data: [15, 12, 19, 14, 22, 28, 35],
                    borderColor: '#263A30',
                    backgroundColor: 'rgba(38, 58, 48, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [2, 4], color: '#f0f0f0' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    }
});
</script>
