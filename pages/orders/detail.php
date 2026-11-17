<?php
// pages/orders/detail.php — Chi tiết đơn hàng của khách hàng chuẩn gốc VAA THÉ

// Trạng thái timeline
$timeline_keys = ['pending', 'confirmed', 'preparing', 'ready', 'completed'];
$timeline_labels = ['Chờ xác nhận', 'Đã xác nhận', 'Đang chuẩn bị', 'Sẵn sàng', 'Hoàn thành'];
$current_step_index = array_search(strtolower($order['status']), $timeline_keys);
if ($current_step_index === false) $current_step_index = -1;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= csrf_meta() ?>
    <title><?= e($title ?? ('Chi tiết đơn hàng #' . $order['id'])) ?> - VAA THÉ</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= e(asset('assets/css/style.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('assets/css/global.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('assets/css/account.css')) ?>">
</head>
<body class="bg-ivory">

    <!-- Header tối giản -->
    <?php include __DIR__ . '/../../layouts/account/header.php'; ?>
    
    <div class="account-page bg-cream py-5 account-style-94abf0">
        <div class="container">
            <div class="row gy-4">
                <!-- Sidebar -->
                <div class="col-lg-3">
                    <?php 
                    $activeTab = 'orders';
                    include __DIR__ . '/../../layouts/account/sidebar.php'; 
                    ?>
                </div>

                <!-- Main Content -->
                <div class="col-lg-9">
                    <div class="d-flex align-items-center mb-4">
                        <a href="<?= e(url('orders')) ?>" class="btn btn-outline-forest btn-sm me-3"><i class="fa-solid fa-arrow-left"></i></a>
                        <h4 class="font-serif fw-bold text-forest mb-0">ORDER #<?= e($order['id']) ?></h4>
                    </div>

                    <!-- Timeline Tracking -->
                    <div class="card border-0 rounded-0 shadow-sm mb-4">
                        <div class="card-body p-4 p-lg-5">
                            <div class="order-timeline position-relative">
                                <div class="progress position-absolute account-style-fb7ddd">
                                    <?php 
                                        $progress_width = 0;
                                        if ($current_step_index > 0) {
                                            $progress_width = ($current_step_index / (count($timeline_keys) - 1)) * 100;
                                        }
                                    ?>
                                    <div class="progress-bar bg-sage" role="progressbar" style="width: <?= $progress_width ?>%" aria-valuenow="<?= $progress_width ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                
                                <div class="d-flex justify-content-between position-relative z-2">
                                    <?php foreach ($timeline_keys as $index => $step_key): ?>
                                        <?php 
                                            $step_class = 'text-muted';
                                            $icon_bg = 'bg-white border-light';
                                            $icon_color = 'text-muted';
                                            
                                            if ($index < $current_step_index) { // Completed step
                                                $step_class = 'text-forest fw-medium';
                                                $icon_bg = 'bg-sage border-sage';
                                                $icon_color = 'text-white';
                                            } else if ($index === $current_step_index) { // Current step
                                                $step_class = 'text-forest fw-bold';
                                                $icon_bg = 'bg-forest border-forest';
                                                $icon_color = 'text-white';
                                            }
                                        ?>
                                        <div class="text-center account-style-f6f6b1">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2 border border-2 <?= $icon_bg ?> <?= $icon_color ?>" style="width: 50px; height: 50px;">
                                                <?php if ($step_key === 'pending'): ?> <i class="fa-regular fa-clipboard"></i>
                                                <?php elseif ($step_key === 'confirmed'): ?> <i class="fa-solid fa-check"></i>
                                                <?php elseif ($step_key === 'preparing'): ?> <i class="fa-solid fa-blender"></i>
                                                <?php elseif ($step_key === 'ready'): ?> <i class="fa-solid fa-motorcycle"></i>
                                                <?php else: ?> <i class="fa-solid fa-house-chimney"></i>
                                                <?php endif; ?>
                                            </div>
                                            <div class="small <?= $step_class ?>"><?= $timeline_labels[$index] ?></div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Info & Items -->
                    <div class="row gy-4">
                        <!-- Items -->
                        <div class="col-lg-7">
                            <div class="card border-0 rounded-0 shadow-sm h-100">
                                <div class="card-header bg-white border-bottom border-sage border-opacity-25 p-4">
                                    <h6 class="font-serif fw-bold text-forest mb-0">ĐẶT HÀNG</h6>
                                </div>
                                <div class="card-body p-0">
                                    <ul class="list-group list-group-flush">
                                        <?php foreach ($order['items'] as $item): ?>
                                        <li class="list-group-item p-4 border-sage border-opacity-25">
                                            <div class="d-flex gap-3">
                                                <img src="<?= e($item['image']) ?>" alt="<?= e($item['name']) ?>" class="rounded-1 object-fit-cover" width="80" height="80">
                                                <div class="flex-grow-1">
                                                    <h6 class="fw-bold text-dark mb-1"><?= e($item['name']) ?></h6>
                                                    <div class="small text-muted mb-2">
                                                        Size: <?= e($item['size']) ?> | Đường: <?= e($item['sugar']) ?> | Đá: <?= e($item['ice']) ?> <br>
                                                        Topping: <?= e($item['toppings']) ?>
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                                        <span class="small fw-medium">Qty: <?= (int)$item['quantity'] ?></span>
                                                        <div class="d-flex align-items-center gap-3">
                                                            <?php if (!empty($item['product_id']) && ($order['status'] === 'Hoàn thành' || strtolower($order['status']) === 'completed')): ?>
                                                                <a href="<?= e(url('product-detail', ['id' => $item['product_id']])) ?>#review-form" class="btn btn-outline-forest btn-sm py-1 px-2 rounded-0 small text-decoration-none">
                                                                    <i class="fa-regular fa-star me-1 text-caramel"></i>Đánh giá món
                                                                </a>
                                                            <?php endif; ?>
                                                            <span class="fw-bold text-caramel"><?= e($item['price']) ?></span>
                                                        </div>
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
                            <div class="card border-0 rounded-0 shadow-sm h-100 bg-ivory">
                                <div class="card-header bg-transparent border-bottom border-sage border-opacity-25 p-4">
                                    <h6 class="font-serif fw-bold text-forest mb-0">TỔNG KẾT ĐƠN HÀNG</h6>
                                </div>
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between mb-3 pb-3 border-bottom border-sage border-opacity-25">
                                        <span class="text-muted small">Ngày đặt hàng</span>
                                        <span class="fw-medium"><?= e($order['date']) ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-3 pb-3 border-bottom border-sage border-opacity-25">
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
                                    <div class="d-flex justify-content-between mb-3 pb-3 border-bottom border-sage border-opacity-25">
                                        <span class="text-muted">Vận chuyển</span>
                                        <span><?= e($order['shipping']) ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold text-dark fs-5">TỔNG CỘNG</span>
                                        <span class="fw-bold text-forest fs-4"><?= e($order['total']) ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= e(asset('assets/js/main.js')) ?>"></script>
</body>
</html>
