<?php
// Trạng thái timeline
$timeline_keys = ['pending', 'confirmed', 'preparing', 'ready', 'completed'];
$timeline_labels = ['Chờ xác nhận', 'Đã xác nhận', 'Đang chuẩn bị', 'Sẵn sàng', 'Hoàn thành'];
$current_step_index = array_search(strtolower($order['status']), $timeline_keys);
if ($current_step_index === false) $current_step_index = -1;
?>
<section class="py-5 bg-cream">
    <div class="container">
        <div class="d-flex align-items-center mb-4">
            <a href="<?= e(url('orders')) ?>" class="btn btn-outline-forest btn-sm me-3 rounded-pill"><i class="fa-solid fa-arrow-left"></i> Quay lại</a>
            <h4 class="font-serif fw-bold text-forest mb-0">ORDER #<?= e($order['id']) ?></h4>
        </div>

        <!-- Timeline Tracking -->
        <div class="card border-0 rounded-4 shadow-sm mb-4">
            <div class="card-body p-4 p-lg-5">
                <div class="order-timeline position-relative">
                    <div class="progress position-absolute" style="height: 4px; top: 25px; left: 5%; right: 5%; z-index: 1;">
                        <?php 
                            $progress_width = 0;
                            if ($current_step_index > 0) {
                                $progress_width = ($current_step_index / (count($timeline_keys) - 1)) * 100;
                            }
                        ?>
                        <div class="progress-bar bg-forest" role="progressbar" style="width: <?= $progress_width ?>%"></div>
                    </div>
                    
                    <div class="d-flex justify-content-between position-relative" style="z-index: 2;">
                        <?php foreach ($timeline_keys as $index => $step_key): ?>
                            <?php 
                                $icon_bg = 'bg-white text-muted border-secondary border-opacity-25';
                                $label_class = 'text-muted';
                                if ($index < $current_step_index) {
                                    $icon_bg = 'bg-success text-white border-success';
                                    $label_class = 'text-forest fw-medium';
                                } elseif ($index === $current_step_index) {
                                    $icon_bg = 'bg-forest text-white border-forest shadow';
                                    $label_class = 'text-forest fw-bold';
                                }
                            ?>
                            <div class="text-center" style="width: 18%;">
                                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2 border border-2 <?= $icon_bg ?>" style="width: 48px; height: 48px;">
                                    <?php if ($step_key === 'pending'): ?><i class="fa-regular fa-clipboard"></i>
                                    <?php elseif ($step_key === 'confirmed'): ?><i class="fa-solid fa-check"></i>
                                    <?php elseif ($step_key === 'preparing'): ?><i class="fa-solid fa-blender"></i>
                                    <?php elseif ($step_key === 'ready'): ?><i class="fa-solid fa-motorcycle"></i>
                                    <?php elseif ($step_key === 'completed'): ?><i class="fa-solid fa-circle-check"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="small <?= $label_class ?>"><?= $timeline_labels[$index] ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Order Items -->
            <div class="col-lg-7">
                <div class="card border-0 rounded-4 shadow-sm h-100 overflow-hidden">
                    <div class="card-header bg-white border-bottom p-4">
                        <h6 class="font-serif fw-bold text-forest mb-0">CHI TIẾT MÓN ĐÃ ĐẶT</h6>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <?php foreach ($order['items'] as $item): ?>
                            <li class="list-group-item p-4">
                                <div class="d-flex gap-3">
                                    <img src="<?= e($item['image']) ?>" alt="<?= e($item['name']) ?>" class="rounded-3 object-fit-cover" width="70" height="70">
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold text-dark mb-1"><?= e($item['name']) ?></h6>
                                        <div class="small text-muted mb-2">
                                            Size: <?= e($item['size']) ?> | Đường: <?= e($item['sugar']) ?> | Đá: <?= e($item['ice']) ?> <br>
                                            Topping: <?= e($item['toppings']) ?>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="small text-muted">Số lượng: x<?= (int)$item['quantity'] ?></span>
                                            <span class="fw-bold text-caramel"><?= e($item['price']) ?></span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Summary -->
            <div class="col-lg-5">
                <div class="card border-0 rounded-4 shadow-sm h-100 bg-white">
                    <div class="card-header bg-transparent border-bottom p-4">
                        <h6 class="font-serif fw-bold text-forest mb-0">TỔNG KẾT ĐƠN HÀNG</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                            <span class="text-muted small">Ngày đặt hàng</span>
                            <span class="fw-medium"><?= e($order['date']) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                            <span class="text-muted small">Trạng thái thanh toán</span>
                            <span class="fw-medium text-success"><?= e($order['payment_status']) ?></span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Tạm tính</span>
                            <span><?= e($order['subtotal']) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Giảm giá</span>
                            <span class="text-success"><?= e($order['discount']) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                            <span class="text-muted">Vận chuyển</span>
                            <span><?= e($order['shipping']) ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-dark fs-5 font-serif">TỔNG CỘNG</span>
                            <span class="fw-bold text-forest fs-4 font-monospace"><?= e($order['total']) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
