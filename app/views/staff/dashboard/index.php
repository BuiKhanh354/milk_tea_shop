<div class="mb-4">
    <h4 class="font-serif fw-bold text-forest mb-1">Xử lý Đơn hàng (Ca hiện tại)</h4>
    <p class="text-muted">Tập trung xử lý các đơn hàng đang chờ hoặc đang pha chế.</p>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card py-3 px-4 bg-warning bg-opacity-10 border-0">
            <h6 class="text-warning fw-bold mb-1">Chờ xác nhận</h6>
            <h3 class="fw-bold mb-0 text-dark"><?= $stats['pending'] ?></h3>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card py-3 px-4 bg-info bg-opacity-10 border-0">
            <h6 class="text-info fw-bold mb-1">Đang pha chế</h6>
            <h3 class="fw-bold mb-0 text-dark"><?= $stats['preparing'] ?></h3>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card py-3 px-4 bg-primary bg-opacity-10 border-0">
            <h6 class="text-primary fw-bold mb-1">Sẵn sàng</h6>
            <h3 class="fw-bold mb-0 text-dark"><?= $stats['ready'] ?></h3>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card py-3 px-4 bg-success bg-opacity-10 border-0">
            <h6 class="text-success fw-bold mb-1">Hoàn thành</h6>
            <h3 class="fw-bold mb-0 text-dark"><?= $stats['completed'] ?></h3>
        </div>
    </div>
</div>

<!-- Active Orders Grid -->
<div class="row g-4">
    <?php if(empty($orders)): ?>
        <div class="col-12 text-center py-5">
            <h5 class="text-muted">Không có đơn hàng nào cần xử lý lúc này.</h5>
        </div>
    <?php else: ?>
        <?php foreach($orders as $order): ?>
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-forest mb-0"><?= $order['code'] ?></h5>
                        <?php 
                        $badgeClass = '';
                        $statusText = '';
                        switch($order['status']) {
                            case 'pending': $badgeClass = 'badge-pending'; $statusText = 'Chờ xác nhận'; break;
                            case 'confirmed': $badgeClass = 'bg-info bg-opacity-10 text-info'; $statusText = 'Đã xác nhận'; break;
                            case 'preparing': $badgeClass = 'badge-preparing'; $statusText = 'Đang chuẩn bị'; break;
                            case 'ready': $badgeClass = 'bg-success bg-opacity-25 text-success'; $statusText = 'Sẵn sàng'; break;
                        }
                        ?>
                        <span class="badge <?= $badgeClass ?> rounded-pill px-3 py-2"><?= $statusText ?></span>
                    </div>
                    
                    <div class="mb-3 text-muted small">
                        <div><i class="fa-regular fa-clock me-2"></i> Đặt lúc: <span class="text-dark fw-medium"><?= $order['time'] ?></span></div>
                        <div><i class="fa-solid fa-user me-2"></i> Khách hàng: <span class="text-dark fw-medium"><?= $order['customer'] ?></span></div>
                        <div><i class="fa-solid fa-bag-shopping me-2"></i> Loại đơn: <span class="text-dark fw-medium"><?= $order['type'] ?></span></div>
                    </div>
                    
                    <h5 class="fw-bold text-dark mb-4"><?= $order['total'] ?></h5>
                    
                    <div class="d-flex gap-2">
                        <form action="admin.php?route=orders&action=update_status" method="POST" class="flex-grow-1">
                            <input type="hidden" name="id" value="<?= $order['id'] ?>">
                            <input type="hidden" name="redirect" value="staff">
                            <?php if($order['status'] === 'pending'): ?>
                                <input type="hidden" name="status" value="confirmed">
                                <button type="submit" class="btn btn-info text-white w-100">Xác nhận</button>
                            <?php elseif($order['status'] === 'confirmed'): ?>
                                <input type="hidden" name="status" value="preparing">
                                <button type="submit" class="btn btn-forest w-100">Bắt đầu pha chế</button>
                            <?php elseif($order['status'] === 'preparing'): ?>
                                <input type="hidden" name="status" value="ready">
                                <button type="submit" class="btn btn-primary w-100">Báo sẵn sàng</button>
                            <?php elseif($order['status'] === 'ready'): ?>
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="btn btn-success w-100">Hoàn thành đơn</button>
                            <?php endif; ?>
                        </form>
                        
                        <a href="admin.php?route=orders&action=show&id=<?= $order['id'] ?>" class="btn btn-light border" data-bs-toggle="tooltip" title="Xem chi tiết">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
