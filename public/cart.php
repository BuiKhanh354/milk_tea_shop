<?php
session_start();

$cart_items = [
    [
        'id' => 1,
        'name' => 'Oolong Sữa Hạnh Nhân',
        'price' => 55000,
        'quantity' => 2,
        'image' => 'https://images.unsplash.com/photo-1576092762791-dd9e2220abd4?auto=format&fit=crop&q=80&w=150'
    ],
    [
        'id' => 2,
        'name' => 'Hồng Trà Kem Phô Mai',
        'price' => 55000,
        'quantity' => 1,
        'image' => 'https://images.unsplash.com/photo-1558160074-4d7d8bdf4256?auto=format&fit=crop&q=80&w=150'
    ]
];

$subtotal = 0;
foreach($cart_items as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$shipping = 15000;
$total = $subtotal + $shipping;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng - VAA THÉ</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .cart-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        .cart-item {
            padding: 1.5rem 0;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        .cart-item:last-child {
            border-bottom: none;
        }
        .qty-btn {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--vaa-sage);
            background: #fff;
            color: var(--vaa-forest);
            transition: all var(--transition-fast);
        }
        .qty-btn:hover {
            background: var(--vaa-sage);
        }
        .qty-input {
            width: 40px;
            text-align: center;
            border: none;
            font-weight: 500;
        }
        .summary-card {
            background-color: #fff;
            padding: 2rem;
            border: 1px solid rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>

    <?php include '../app/views/partials/navbar_old.php'; ?>
    
    <main class="section-padding bg-ivory" style="min-height: calc(100vh - 300px);">
        <div class="container">
            <div class="cart-header fade-up visible">
                <span class="small-label mb-2 d-inline-block">YOUR CART</span>
                <h2 class="font-serif fw-bold text-forest">Giỏ hàng</h2>
            </div>

            <div class="row g-5 fade-up visible">
                <!-- Cart Items -->
                <div class="col-lg-8">
                    <div class="bg-white p-4 p-md-5 border" style="border-color: rgba(0,0,0,0.05)!important;">
                        <?php if(empty($cart_items)): ?>
                            <div class="text-center py-5">
                                <p class="text-muted">Giỏ hàng của bạn đang trống.</p>
                                <a href="products.php" class="btn btn-caramel mt-3">TIẾP TỤC MUA SẮM</a>
                            </div>
                        <?php else: ?>
                            <?php foreach($cart_items as $item): ?>
                            <div class="cart-item d-flex align-items-center flex-wrap gap-4">
                                <img src="<?= $item['image'] ?>" alt="<?= $item['name'] ?>" width="100" height="100" class="object-fit-cover rounded">
                                <div class="flex-grow-1">
                                    <h5 class="font-serif fw-bold mb-1"><?= $item['name'] ?></h5>
                                    <p class="text-caramel fw-medium mb-3"><?= number_format($item['price'], 0, ',', '.') ?>đ</p>
                                    
                                    <div class="d-flex align-items-center">
                                        <div class="d-flex align-items-center border border-sage rounded overflow-hidden">
                                            <button class="btn qty-btn border-0"><i class="fa-solid fa-minus fs-6"></i></button>
                                            <input type="text" class="qty-input" value="<?= $item['quantity'] ?>" readonly>
                                            <button class="btn qty-btn border-0"><i class="fa-solid fa-plus fs-6"></i></button>
                                        </div>
                                        <button class="btn btn-link text-danger text-decoration-none ms-4 small"><i class="fa-regular fa-trash-can me-1"></i> Xóa</button>
                                    </div>
                                </div>
                                <div class="text-end fw-bold text-dark fs-5">
                                    <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?>đ
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="col-lg-4">
                    <div class="summary-card position-sticky" style="top: 100px;">
                        <h4 class="font-serif fw-bold mb-4 pb-3 border-bottom">Tóm tắt đơn hàng</h4>
                        
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Tạm tính</span>
                            <span class="fw-medium"><?= number_format($subtotal, 0, ',', '.') ?>đ</span>
                        </div>
                        <div class="d-flex justify-content-between mb-4 pb-4 border-bottom">
                            <span class="text-muted">Phí giao hàng</span>
                            <span class="fw-medium"><?= number_format($shipping, 0, ',', '.') ?>đ</span>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <span class="fw-bold fs-5">Tổng cộng</span>
                            <span class="fw-bold text-caramel fs-3"><?= number_format($total, 0, ',', '.') ?>đ</span>
                        </div>
                        
                        <button class="btn btn-caramel w-100 py-3 fw-bold tracking-wide">TIẾN HÀNH THANH TOÁN</button>
                        <div class="text-center mt-3">
                            <a href="products.php" class="text-muted small text-decoration-none hover-caramel transition-fast"><i class="fa-solid fa-arrow-left me-1"></i> Tiếp tục mua sắm</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <?php include '../app/views/layouts/footer.html'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
