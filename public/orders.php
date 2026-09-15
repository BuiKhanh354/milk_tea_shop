<?php
session_start();
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit;
}

$orders = [
    [
        'id' => 'VT001',
        'date' => '15/09/2026',
        'items' => 'Oolong Sữa Hạnh Nhân (x2), Hồng Trà Kem Phô Mai (x1)',
        'total' => '165.000đ',
        'status' => 'Đang chuẩn bị'
    ],
    [
        'id' => 'VT002',
        'date' => '10/09/2026',
        'items' => 'Trà Lài Trái Cây Nhiệt Đới (x1)',
        'total' => '60.000đ',
        'status' => 'Hoàn thành'
    ]
];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn đã mua - VAA THÉ</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .order-card {
            border: 1px solid rgba(0,0,0,0.05);
            transition: all var(--transition-normal);
        }
        .order-card:hover {
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            transform: translateY(-2px);
        }
        .status-badge {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 0.5rem 1rem;
            border-radius: 50px;
        }
        .status-preparing {
            background-color: var(--vaa-cream);
            color: var(--vaa-caramel);
        }
        .status-completed {
            background-color: var(--vaa-sage);
            color: var(--vaa-forest);
        }
    </style>
</head>
<body class="bg-ivory">

    <!-- Header tối giản -->
    <?php include '../app/views/partials/account-header.php'; ?>
    
    <main class="container py-5" style="min-height: calc(100vh - 70px);">
        <div class="row g-4">
            <!-- Sidebar (Cột Trái) -->
            <div class="col-lg-3">
                <?php include '../app/views/partials/account-sidebar.php'; ?>
            </div>
            
            <!-- Content (Cột Phải) -->
            <div class="col-lg-9">
                <div class="bg-white p-4 p-md-5 border border-sage border-opacity-25 shadow-sm h-100 fade-up visible">
                    
                    <div class="mb-5 pb-3 border-bottom border-light">
                        <span class="small-label d-block mb-1">ORDER HISTORY</span>
                        <h3 class="font-serif fw-bold text-forest mb-0">Đơn hàng của bạn</h3>
                    </div>

                    <?php if(empty($orders)): ?>
                        <div class="text-center py-5">
                            <i class="fa-solid fa-receipt fs-1 text-muted opacity-25 mb-3"></i>
                            <h4 class="font-serif text-forest">Chưa có đơn hàng nào</h4>
                            <p class="text-muted">Bạn chưa thực hiện bất kỳ giao dịch nào.</p>
                            <a href="products.php" class="btn btn-caramel mt-3">XEM MENU</a>
                        </div>
                    <?php else: ?>
                        <div class="d-flex flex-column gap-4">
                            <?php foreach($orders as $order): ?>
                            <div class="order-card p-4 rounded-0 bg-ivory">
                                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 pb-3 border-bottom border-sage border-opacity-25">
                                    <div>
                                        <h5 class="font-serif fw-bold text-forest mb-1">Mã đơn: #<?= $order['id'] ?></h5>
                                        <span class="text-muted small">Ngày đặt: <?= $order['date'] ?></span>
                                    </div>
                                    <div>
                                        <?php 
                                            $badgeClass = $order['status'] === 'Hoàn thành' ? 'status-completed' : 'status-preparing';
                                        ?>
                                        <span class="status-badge <?= $badgeClass ?> fw-bold"><?= $order['status'] ?></span>
                                    </div>
                                </div>
                                <div class="row align-items-center">
                                    <div class="col-md-8 mb-3 mb-md-0">
                                        <p class="mb-0 text-dark fw-medium"><?= $order['items'] ?></p>
                                    </div>
                                    <div class="col-md-4 text-md-end">
                                        <p class="small text-muted mb-1">Tổng tiền</p>
                                        <h4 class="text-caramel fw-bold mb-0"><?= $order['total'] ?></h4>
                                    </div>
                                </div>
                                <div class="mt-4 text-end">
                                    <a href="#" class="btn btn-outline-forest btn-sm px-4 py-2">XEM CHI TIẾT</a>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                </div>
            </div>
        </div>
    </main>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
