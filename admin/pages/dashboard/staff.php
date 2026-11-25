<?php
// admin/pages/dashboard/staff.php — Giao diện Staff Dashboard xử lý đơn hàng theo ca làm việc chuẩn gốc VAA THÉ
?>
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="font-serif fw-bold text-forest mb-1">Xử lý Đơn hàng (Ca làm việc)</h4>
        <p class="text-muted small mb-0">Tập trung xử lý các đơn hàng đang chờ hoặc đang pha chế.</p>
    </div>
    <div>
        <?php if (!empty($staffOpenSession)): ?>
            <a href="<?= e(admin_url('shift_sessions', ['action' => 'close'])) ?>" class="btn btn-sm btn-outline-success rounded-pill px-3 py-2 shadow-sm">
                <i class="fa-solid fa-cash-register me-1"></i> Đang mở: <b><?= e($staffOpenSession['shift_name'] ?? $staffOpenSession['session_code']) ?></b> (Kết ca)
            </a>
        <?php elseif (!empty($staffTodayShift)): ?>
            <a href="<?= e(admin_url('shift_sessions', ['action' => 'open'])) ?>" class="btn btn-sm btn-forest rounded-pill px-3 py-2 shadow-sm fw-medium">
                <i class="fa-solid fa-business-time me-1"></i> Vào ca hôm nay: <b><?= e($staffTodayShift['shift_name']) ?> (<?= substr($staffTodayShift['start_time'], 0, 5) ?> - <?= substr($staffTodayShift['end_time'], 0, 5) ?>)</b>
            </a>
        <?php else: ?>
            <span class="badge bg-light text-muted border rounded-pill px-3 py-2 small">
                <i class="fa-solid fa-calendar-xmark me-1 text-secondary"></i> Chưa có ca được phân công hôm nay
            </span>
        <?php endif; ?>
    </div>
</div>

<?php if ($flashSuccess = get_flash('success')): ?>
<div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
    <i class="fa-solid fa-circle-check me-2"></i> <?= e($flashSuccess) ?>
    <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if ($flashError = get_flash('error')): ?>
<div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
    <i class="fa-solid fa-triangle-exclamation me-2"></i> <?= e($flashError) ?>
    <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- 4 Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card py-3 px-4 bg-warning bg-opacity-10 border-0">
            <h6 class="text-warning fw-bold mb-1">Chờ xác nhận</h6>
            <h3 class="fw-bold mb-0 text-dark"><?= (int)($stats['pending'] ?? 0) ?></h3>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card py-3 px-4 bg-info bg-opacity-10 border-0">
            <h6 class="text-info fw-bold mb-1">Đang pha chế</h6>
            <h3 class="fw-bold mb-0 text-dark"><?= (int)($stats['preparing'] ?? 0) ?></h3>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card py-3 px-4 bg-primary bg-opacity-10 border-0">
            <h6 class="text-primary fw-bold mb-1">Sẵn sàng</h6>
            <h3 class="fw-bold mb-0 text-dark"><?= (int)($stats['ready'] ?? 0) ?></h3>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card py-3 px-4 bg-success bg-opacity-10 border-0">
            <h6 class="text-success fw-bold mb-1">Hoàn thành</h6>
            <h3 class="fw-bold mb-0 text-dark"><?= (int)($stats['completed'] ?? 0) ?></h3>
        </div>
    </div>
</div>

<!-- Active Orders Grid -->
<div class="row g-4">
    <?php if (empty($orders)): ?>
        <div class="col-12 text-center py-5">
            <div class="mb-3 text-muted">
                <i class="fa-solid fs-1 text-secondary opacity-50"></i>
            </div>
            <h5 class="text-muted fw-normal">Không có đơn hàng nào cần xử lý lúc này.</h5>
            <p class="small text-muted mb-0">Các đơn hàng mới phát sinh từ Website hoặc POS sẽ hiển thị tại đây.</p>
        </div>
    <?php else: ?>
        <?php foreach ($orders as $order): ?>
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-forest mb-0"><?= e($order['code']) ?></h5>
                        <?php 
                        $badgeClass = '';
                        $statusText = '';
                        switch ($order['status']) {
                            case 'pending':
                                $badgeClass = 'bg-warning bg-opacity-10 text-warning';
                                $statusText = 'Chờ xác nhận';
                                break;
                            case 'confirmed':
                                $badgeClass = 'bg-info bg-opacity-10 text-info';
                                $statusText = 'Đã xác nhận';
                                break;
                            case 'preparing':
                                $badgeClass = 'bg-primary bg-opacity-10 text-primary';
                                $statusText = 'Đang pha chế';
                                break;
                            case 'ready':
                                $badgeClass = 'bg-success bg-opacity-25 text-success';
                                $statusText = 'Sẵn sàng';
                                break;
                            default:
                                $badgeClass = 'bg-secondary bg-opacity-10 text-secondary';
                                $statusText = ucfirst($order['status']);
                                break;
                        }
                        ?>
                        <span class="badge <?= $badgeClass ?> rounded-pill px-3 py-2"><?= $statusText ?></span>
                    </div>
                    
                    <div class="mb-3 text-muted small flex-grow-1">
                        <div class="mb-1"><i class="fa-regular fa-clock me-2 text-caramel"></i> Đặt lúc: <span class="text-dark fw-medium"><?= e($order['time']) ?></span></div>
                        <div class="mb-1"><i class="fa-solid fa-user me-2 text-caramel"></i> Khách hàng: <span class="text-dark fw-medium"><?= e($order['customer']) ?></span></div>
                        <div><i class="fa-solid fa-bag-shopping me-2 text-caramel"></i> Loại đơn: <span class="text-dark fw-medium"><?= e($order['type']) ?></span></div>
                    </div>
                    
                    <h5 class="fw-bold text-dark mb-4"><?= e($order['total']) ?></h5>
                    
                    <div class="d-flex gap-2 mt-auto">
                        <form action="<?= e(admin_url('orders', ['action' => 'update_status'])) ?>" method="POST" class="flex-grow-1">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= (int)$order['id'] ?>">
                            <input type="hidden" name="redirect" value="staff">
                            <?php if ($order['status'] === 'pending'): ?>
                                <input type="hidden" name="status" value="confirmed">
                                <button type="submit" class="btn btn-info text-white w-100 rounded-pill fw-medium">Xác nhận</button>
                            <?php elseif ($order['status'] === 'confirmed'): ?>
                                <input type="hidden" name="status" value="preparing">
                                <button type="submit" class="btn btn-forest w-100 rounded-pill fw-medium">Bắt đầu pha chế</button>
                            <?php elseif ($order['status'] === 'preparing'): ?>
                                <input type="hidden" name="status" value="ready">
                                <button type="submit" class="btn btn-primary w-100 rounded-pill fw-medium">Báo sẵn sàng</button>
                            <?php elseif ($order['status'] === 'ready'): ?>
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="btn btn-success w-100 rounded-pill fw-medium">Hoàn thành đơn</button>
                            <?php endif; ?>
                        </form>
                        
                        <a href="<?= e(admin_url('order-detail', ['id' => $order['id']])) ?>" class="btn btn-light border rounded-pill px-3" data-bs-toggle="tooltip" title="Xem chi tiết đơn hàng">
                            <i class="fa-solid fa-eye text-muted"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
