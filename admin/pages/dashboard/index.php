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
                <span class="badge bg-success bg-opacity-10 text-success rounded-pill fw-medium px-2 py-1">+12.5%</span>
            </div>
            <h6 class="text-muted fw-semibold mb-1">Tổng doanh thu</h6>
            <h3 class="fw-bold mb-0 text-dark"><?= money($totalRevenue) ?></h3>
        </div>
    </div>
    
    <div class="col-12 col-md-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon bg-info bg-opacity-10 text-info">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <span class="badge bg-success bg-opacity-10 text-success rounded-pill fw-medium px-2 py-1">+8.2%</span>
            </div>
            <h6 class="text-muted fw-semibold mb-1">Đơn hàng</h6>
            <h3 class="fw-bold mb-0 text-dark"><?= number_format($totalOrders) ?></h3>
        </div>
    </div>
    
    <div class="col-12 col-md-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="fa-solid fa-users"></i>
                </div>
                <span class="badge bg-success bg-opacity-10 text-success rounded-pill fw-medium px-2 py-1">+5.0%</span>
            </div>
            <h6 class="text-muted fw-semibold mb-1">Khách hàng</h6>
            <h3 class="fw-bold mb-0 text-dark"><?= number_format($totalCustomers) ?></h3>
        </div>
    </div>
    
    <div class="col-12 col-md-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="fa-solid fa-mug-hot"></i>
                </div>
                <span class="badge bg-success bg-opacity-10 text-success rounded-pill fw-medium px-2 py-1">+2 mới</span>
            </div>
            <h6 class="text-muted fw-semibold mb-1">Sản phẩm đang bán</h6>
            <h3 class="fw-bold mb-0 text-dark"><?= number_format($totalProducts) ?></h3>
        </div>
    </div>
</div>

<!-- Biểu đồ & Đơn hàng gần đây -->
<div class="row g-4 mb-4">
    <!-- Chart -->
    <div class="col-12 col-xl-8">
        <div class="stat-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold text-dark mb-0">Biểu đồ doanh thu tuần này</h5>
                <span class="badge bg-light text-forest border">Cập nhật tự động</span>
            </div>
            <div class="revenue-chart-container" style="height: 300px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Tóm tắt trạng thái -->
    <div class="col-12 col-xl-4">
        <div class="stat-card h-100 d-flex flex-column">
            <h5 class="fw-bold text-dark mb-4">Trạng thái hệ thống</h5>
            <div class="d-flex flex-column gap-3">
                <div class="p-3 bg-light rounded-3 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-bold text-forest">Máy chủ MySQL</div>
                        <small class="text-muted">Cơ sở dữ liệu vaa_the</small>
                    </div>
                    <span class="badge bg-success">Online</span>
                </div>
                <div class="p-3 bg-light rounded-3 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-bold text-forest">Kiến trúc</div>
                        <small class="text-muted">Modular PHP Thuần</small>
                    </div>
                    <span class="badge bg-primary">Chuẩn Giáo Viên</span>
                </div>
                <div class="p-3 bg-light rounded-3 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-bold text-forest">Phương thức thanh toán</div>
                        <small class="text-muted">COD & MoMo QR</small>
                    </div>
                    <span class="badge bg-warning text-dark">Sẵn sàng</span>
                </div>
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
            <thead class="text-muted small border-bottom">
                <tr>
                    <th>Mã đơn</th>
                    <th>Khách hàng</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Thời gian</th>
                    <th class="text-end">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($recentOrders as $order): ?>
                <tr>
                    <td class="fw-bold text-forest">#<?= (int)$order['id'] ?></td>
                    <td><?= e($order['customer_name'] ?? 'Khách vãng lai') ?></td>
                    <td class="fw-bold text-caramel"><?= money($order['final_amount']) ?></td>
                    <td>
                        <span class="badge <?= $order['status'] === 'completed' ? 'bg-success' : ($order['status'] === 'cancelled' ? 'bg-danger' : 'bg-warning text-dark') ?> rounded-pill px-3 py-1">
                            <?= e($order['status']) ?>
                        </span>
                    </td>
                    <td class="text-muted small"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                    <td class="text-end">
                        <a href="<?= e(admin_url('orders')) ?>" class="btn btn-sm btn-light border shadow-none" title="Xem chi tiết">
                            <i class="fa-solid fa-eye text-muted"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('revenueChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'],
                datasets: [{
                    label: 'Doanh thu (Triệu VNĐ)',
                    data: [1.2, 2.5, 1.8, 3.2, 2.9, 4.5, 5.1],
                    borderColor: '#263A30',
                    backgroundColor: 'rgba(38, 58, 48, 0.08)',
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
