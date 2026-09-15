<?php
// Mock Data cho Order Detail
$order_id = $order_id ?? 'VT001';
$order = [
    'id' => $order_id,
    'date' => '15/09/2026 14:30',
    'status' => 'Preparing', // Pending, Confirmed, Preparing, Ready, Completed
    'payment_status' => 'Paid (VNPay)',
    'subtotal' => '165.000đ',
    'discount' => '-15.000đ',
    'shipping' => '15.000đ',
    'total' => '165.000đ',
    'items' => [
        [
            'name' => 'Oolong Sữa Hạnh Nhân',
            'size' => 'L',
            'sugar' => '50%',
            'ice' => '50%',
            'toppings' => 'Trân châu trắng',
            'quantity' => 2,
            'price' => '110.000đ',
            'image' => 'https://images.unsplash.com/photo-1576092762791-dd9e2220abd4?auto=format&fit=crop&q=80&w=150'
        ],
        [
            'name' => 'Hồng Trà Kem Phô Mai',
            'size' => 'M',
            'sugar' => '100%',
            'ice' => '100%',
            'toppings' => 'Không',
            'quantity' => 1,
            'price' => '55.000đ',
            'image' => 'https://images.unsplash.com/photo-1558160074-4d7d8bdf4256?auto=format&fit=crop&q=80&w=150'
        ]
    ]
];

// Trạng thái timeline
$timeline_steps = ['Pending', 'Confirmed', 'Preparing', 'Ready', 'Completed'];
$current_step_index = array_search($order['status'], $timeline_steps);
if ($current_step_index === false) $current_step_index = -1;
?>

<div class="account-page bg-cream py-5" style="min-height: calc(100vh - 300px); padding-top: 120px !important;">
    <div class="container">
        <div class="row gy-4">
            <!-- Sidebar -->
            <div class="col-lg-3">
                <?php require_once __DIR__ . '/../../partials/account-sidebar.php'; ?>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9">
                <div class="d-flex align-items-center mb-4">
                    <a href="<?= BASE_URL ?>/orders" class="btn btn-outline-forest btn-sm me-3"><i class="fa-solid fa-arrow-left"></i></a>
                    <h4 class="font-serif fw-bold text-forest mb-0">ORDER #<?= $order['id'] ?></h4>
                </div>

                <!-- Timeline Tracking -->
                <div class="card border-0 rounded-0 shadow-sm mb-4">
                    <div class="card-body p-4 p-lg-5">
                        <div class="order-timeline position-relative">
                            <div class="progress position-absolute" style="height: 4px; top: 24px; left: 10%; right: 10%; z-index: 1;">
                                <?php 
                                    $progress_width = 0;
                                    if ($current_step_index > 0) {
                                        $progress_width = ($current_step_index / (count($timeline_steps) - 1)) * 100;
                                    }
                                ?>
                                <div class="progress-bar bg-sage" role="progressbar" style="width: <?= $progress_width ?>%" aria-valuenow="<?= $progress_width ?>" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            
                            <div class="d-flex justify-content-between position-relative z-2">
                                <?php foreach ($timeline_steps as $index => $step): ?>
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
                                    <div class="text-center" style="width: 20%;">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2 border border-2 <?= $icon_bg ?> <?= $icon_color ?>" style="width: 50px; height: 50px;">
                                            <?php if($step === 'Pending'): ?> <i class="fa-regular fa-clipboard"></i>
                                            <?php elseif($step === 'Confirmed'): ?> <i class="fa-solid fa-check"></i>
                                            <?php elseif($step === 'Preparing'): ?> <i class="fa-solid fa-blender"></i>
                                            <?php elseif($step === 'Ready'): ?> <i class="fa-solid fa-motorcycle"></i>
                                            <?php else: ?> <i class="fa-solid fa-house-chimney"></i>
                                            <?php endif; ?>
                                        </div>
                                        <div class="small <?= $step_class ?>"><?= $step ?></div>
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
                                <h6 class="font-serif fw-bold text-forest mb-0">ORDER ITEMS</h6>
                            </div>
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush">
                                    <?php foreach($order['items'] as $item): ?>
                                    <li class="list-group-item p-4 border-sage border-opacity-25">
                                        <div class="d-flex gap-3">
                                            <img src="<?= $item['image'] ?>" alt="<?= $item['name'] ?>" class="rounded-1 object-fit-cover" width="80" height="80">
                                            <div class="flex-grow-1">
                                                <h6 class="fw-bold text-dark mb-1"><?= $item['name'] ?></h6>
                                                <div class="small text-muted mb-2">
                                                    Size: <?= $item['size'] ?> | Sugar: <?= $item['sugar'] ?> | Ice: <?= $item['ice'] ?> <br>
                                                    Topping: <?= $item['toppings'] ?>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="small fw-medium">Qty: <?= $item['quantity'] ?></span>
                                                    <span class="fw-bold text-caramel"><?= $item['price'] ?></span>
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
                                <h6 class="font-serif fw-bold text-forest mb-0">ORDER SUMMARY</h6>
                            </div>
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between mb-3 pb-3 border-bottom border-sage border-opacity-25">
                                    <span class="text-muted small">Order Date</span>
                                    <span class="fw-medium"><?= $order['date'] ?></span>
                                </div>
                                <div class="d-flex justify-content-between mb-3 pb-3 border-bottom border-sage border-opacity-25">
                                    <span class="text-muted small">Payment Status</span>
                                    <span class="fw-medium text-success"><?= $order['payment_status'] ?></span>
                                </div>
                                
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Subtotal</span>
                                    <span><?= $order['subtotal'] ?></span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Discount</span>
                                    <span class="text-success"><?= $order['discount'] ?></span>
                                </div>
                                <div class="d-flex justify-content-between mb-3 pb-3 border-bottom border-sage border-opacity-25">
                                    <span class="text-muted">Shipping</span>
                                    <span><?= $order['shipping'] ?></span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-dark fs-5">TOTAL</span>
                                    <span class="fw-bold text-forest fs-4"><?= $order['total'] ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
