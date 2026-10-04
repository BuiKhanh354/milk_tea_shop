<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Thanh toán - RTea' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .payment-method-option {
            transition: all 0.2s ease;
        }
        .payment-method-option:hover {
            background-color: #f8f9fa !important;
            border-color: #dee2e6 !important;
        }
        .payment-method-option input:checked ~ label .fw-bold {
            color: var(--forest-green) !important;
        }
        .payment-method-option:has(input:checked) {
            border-color: var(--forest-green) !important;
            background-color: #f0f7f4 !important;
        }
    </style>
</head>
<body class="bg-light">

<?php include __DIR__ . '/../../partials/navbar_old.php'; ?>

<div class="container-fluid bg-light py-5">
    <div class="container">
        <h2 class="font-serif fw-bold text-black mb-4">Thanh toán</h2>

        <form action="index.php?route=checkout&action=process" method="POST" id="checkout-form">
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4 p-md-5">
                        <h5 class="fw-bold mb-4">Thông tin giao hàng</h5>
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold">HỌ VÀ TÊN</label>
                                <input type="text" name="name" class="form-control form-control-lg bg-light border-0" required placeholder="Nhập họ và tên của bạn" value="<?= htmlspecialchars($customer['full_name'] ?? '') ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold">SỐ ĐIỆN THOẠI</label>
                                <input type="tel" name="phone" class="form-control form-control-lg bg-light border-0" required placeholder="Nhập số điện thoại" value="<?= htmlspecialchars($customer['phone'] ?? '') ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold">ĐỊA CHỈ GIAO HÀNG</label>
                                <textarea name="address" class="form-control form-control-lg bg-light border-0" rows="3" required placeholder="Nhập địa chỉ giao hàng chi tiết"><?= htmlspecialchars($customer['address'] ?? '') ?></textarea>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label text-muted small fw-bold">GHI CHÚ (TÙY CHỌN)</label>
                                <textarea name="note" class="form-control form-control-lg bg-light border-0" rows="2" placeholder="Ghi chú thêm về đơn hàng..."></textarea>
                            </div>

                            <h5 class="fw-bold mb-3 mt-5">Phương thức thanh toán</h5>
                            
                            <div class="form-check p-3 border rounded-3 mb-2 bg-white payment-method-option" onclick="this.querySelector('input').checked = true;">
                                <input class="form-check-input ms-1" type="radio" name="payment_method" id="pay_cash" value="cash" checked>
                                <label class="form-check-label ms-2 d-flex align-items-center w-100" for="pay_cash" style="cursor: pointer;">
                                    <i class="fa-solid fa-money-bill-wave text-success fs-4 me-3"></i>
                                    <div>
                                        <span class="d-block fw-bold text-dark">Thanh toán khi nhận hàng (COD)</span>
                                        <span class="d-block text-muted small">Thanh toán bằng tiền mặt khi giao hàng</span>
                                    </div>
                                </label>
                            </div>

                            <div class="form-check p-3 border rounded-3 mb-2 bg-white payment-method-option" onclick="this.querySelector('input').checked = true;">
                                <input class="form-check-input ms-1" type="radio" name="payment_method" id="pay_transfer" value="bank_transfer">
                                <label class="form-check-label ms-2 d-flex align-items-center w-100" for="pay_transfer" style="cursor: pointer;">
                                    <i class="fa-solid fa-building-columns text-primary fs-4 me-3"></i>
                                    <div>
                                        <span class="d-block fw-bold text-dark">Chuyển khoản ngân hàng</span>
                                        <span class="d-block text-muted small">Quét mã QR để thanh toán</span>
                                    </div>
                                </label>
                            </div>

                            <div class="form-check p-3 border rounded-3 mb-4 bg-white payment-method-option" onclick="this.querySelector('input').checked = true;">
                                <input class="form-check-input ms-1" type="radio" name="payment_method" id="pay_momo" value="momo">
                                <label class="form-check-label ms-2 d-flex align-items-center w-100" for="pay_momo" style="cursor: pointer;">
                                    <i class="fa-solid fa-wallet text-danger fs-4 me-3"></i>
                                    <div>
                                        <span class="d-block fw-bold text-dark">Ví MoMo</span>
                                        <span class="d-block text-muted small">Thanh toán qua ví điện tử MoMo</span>
                                    </div>
                                </label>
                            </div>

                            <button type="submit" class="btn btn-caramel w-100 py-3 fw-bold rounded-pill text-white mt-3 shadow" style="font-size: 1.1rem;">
                                ĐẶT HÀNG NGAY
                            </button>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 position-sticky" style="top: 100px;">
                    <div class="card-body p-4 p-md-5">
                        <h5 class="fw-bold mb-4">Tóm tắt đơn hàng</h5>
                        
                        <div class="mb-4">
                            <?php foreach($cart_items as $item): ?>
                                <div class="d-flex align-items-start mb-3 pb-3 border-bottom">
                                    <div class="position-relative me-3">
                                        <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="rounded-3 object-fit-cover" width="60" height="60">
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-caramel border border-white">
                                            <?= $item['quantity'] ?>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold text-dark mb-1"><?= htmlspecialchars($item['name']) ?></h6>
                                        <div class="small text-muted mb-1">
                                            <?= !empty($item['size_name']) ? "Size " . htmlspecialchars($item['size_name']) : "" ?>
                                            <?php if (!empty($item['toppings'])): ?>
                                                + <?= count($item['toppings']) ?> Topping
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="fw-bold text-dark">
                                        <?= number_format($item['total_price'] * $item['quantity'], 0, ',', '.') ?>đ
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="d-flex justify-content-between mb-2 text-muted">
                            <span>Tạm tính</span>
                            <span><?= number_format($subtotal, 0, ',', '.') ?>đ</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 text-muted border-bottom pb-3">
                            <span>Phí vận chuyển</span>
                            <span><?= number_format($shipping, 0, ',', '.') ?>đ</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="fw-bold fs-5 text-dark">Tổng cộng</span>
                            <span class="fw-bold fs-4 text-caramel"><?= number_format($total, 0, ',', '.') ?>đ</span>
                        </div>
                        

                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
