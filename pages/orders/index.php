<?php
if ($action === 'detail') {
    require __DIR__ . '/detail.php';
    return;
}
?>
<section class="orders-section py-5 bg-cream">
    <div class="container">
        
        <div class="mb-4 pb-2 border-bottom border-secondary border-opacity-10">
            <span class="small text-muted text-uppercase fw-bold letter-spacing-1 d-block mb-1">LỊCH SỬ GIAO DỊCH</span>
            <h2 class="font-serif fw-bold text-forest mb-0">Đơn hàng của bạn</h2>
        </div>

        <?php if (empty($orders)): ?>
            <div class="text-center py-5 bg-white rounded-4 shadow-sm p-4">
                <i class="fa-solid fa-receipt fa-3x text-muted opacity-25 mb-3"></i>
                <h4 class="font-serif text-forest mb-2">Chưa có đơn hàng nào</h4>
                <p class="text-muted mb-4">Bạn chưa thực hiện bất kỳ giao dịch nào tại VAA THÉ.</p>
                <a href="<?= e(url('products')) ?>" class="btn btn-forest px-4 py-2 rounded-pill fw-semibold">XEM MENU & ĐẶT HÀNG</a>
            </div>
        <?php else: ?>
            <div class="d-flex flex-column gap-3">
                <?php foreach ($orders as $order): ?>
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white" style="transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div>
                            <h5 class="font-serif fw-bold text-forest mb-1">Mã đơn: #<?= e($order['id']) ?></h5>
                            <span class="text-muted small"><i class="fa-regular fa-clock me-1"></i> Ngày đặt: <?= e($order['date']) ?></span>
                        </div>
                        <div>
                            <?php 
                                $badgeClass = 'bg-warning text-dark';
                                if ($order['status'] === 'Hoàn thành') $badgeClass = 'bg-success text-white';
                                elseif ($order['status'] === 'Đã hủy') $badgeClass = 'bg-danger text-white';
                                elseif ($order['status'] === 'Đang pha chế' || $order['status'] === 'Đã xác nhận') $badgeClass = 'bg-primary text-white';
                            ?>
                            <span class="badge <?= $badgeClass ?> rounded-pill px-3 py-2 fw-medium"><?= e($order['status']) ?></span>
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-md-8 mb-3 mb-md-0">
                            <p class="mb-0 text-dark fw-medium"><i class="fa-solid fa-mug-hot text-forest me-2"></i><?= e($order['items']) ?></p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <p class="small text-muted mb-1">Tổng tiền</p>
                            <h4 class="text-forest fw-bold mb-0 font-monospace"><?= e($order['total']) ?></h4>
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-top d-flex justify-content-end">
                        <a href="<?= e(url('orders', ['action' => 'detail', 'id' => $order['raw_id']])) ?>" class="btn btn-outline-forest btn-sm rounded-pill px-4 py-2">
                            XEM CHI TIẾT <i class="fa-solid fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</section>
