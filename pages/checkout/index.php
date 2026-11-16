<!-- pages/checkout/index.php — Giao diện Thanh Toán & Xác Nhận Đơn chuẩn gốc VAA THÉ -->
<style>
    .payment-method-option {
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .payment-method-option:hover {
        background-color: #f8f9fa !important;
        border-color: #dee2e6 !important;
    }

    .payment-method-option input:checked~label .fw-bold {
        color: var(--vaa-forest) !important;
    }

    .payment-method-option:has(input:checked) {
        border-color: var(--vaa-forest) !important;
        background-color: #f0f7f4 !important;
    }
</style>

<?php if (!empty($isSuccess)): ?>
<!-- Giao diện Đặt hàng thành công -->
<div class="container-fluid bg-light py-5 min-vh-100 d-flex align-items-center">
    <div class="container text-center">
        <div class="card border-0 shadow-sm rounded-4 mx-auto global-style-4d1e5c" style="max-width: 600px;">
            <div class="card-body p-5">
                <div class="mb-4">
                    <i class="fa-solid fa-circle-check text-success global-style-7eebeb" style="font-size: 5rem;"></i>
                </div>
                <h2 class="font-serif fw-bold text-dark mb-3">Đặt hàng thành công!</h2>
                <p class="text-muted mb-4 fs-5">
                    Cảm ơn bạn đã tin tưởng và ủng hộ VAA THÉ.
                    <?php if (!empty($successOrderId)): ?>
                        <br>Mã đơn hàng của bạn là <strong>#<?= (int)$successOrderId ?></strong>.
                    <?php endif; ?>
                    <br>Đơn hàng của bạn đang được xử lý và sẽ sớm được giao đến bạn.
                </p>
                <div class="d-flex justify-content-center gap-3 mt-4">
                    <a href="<?= e(url('products')) ?>" class="btn btn-outline-forest py-2 px-4 rounded-pill fw-bold">
                        TIẾP TỤC MUA SẮM
                    </a>
                    <?php if (isset($_SESSION['customer_id'])): ?>
                    <a href="<?= e(url('orders')) ?>" class="btn btn-forest py-2 px-4 rounded-pill fw-bold">
                        XEM ĐƠN HÀNG
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php else: ?>

<!-- Giao diện Xác nhận & Thanh toán đơn hàng -->
<div class="container-fluid bg-light page-offset pb-5 py-4">
    <div class="container">
        <h2 class="font-serif fw-bold text-black mb-4">Thanh toán</h2>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger rounded-3 mb-4">
                <ul class="mb-0">
                    <?php foreach ($errors as $err): ?>
                        <li><?= e($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= e(url('checkout', ['action' => 'process'])) ?>" method="POST" id="checkout-form">
            <?= csrf_field() ?>
            <div class="row g-4">
                <!-- Cột thông tin giao hàng & phương thức thanh toán -->
                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4 p-md-5">
                            <h5 class="fw-bold mb-4 font-serif text-forest">Thông tin giao hàng</h5>
                            
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold">HỌ VÀ TÊN *</label>
                                <input type="text" name="name" class="form-control form-control-lg bg-light border-0" required placeholder="Nhập họ và tên của bạn" value="<?= e($customer['full_name'] ?? ($_SESSION['customer_name'] ?? '')) ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold">SỐ ĐIỆN THOẠI *</label>
                                <input type="tel" name="phone" class="form-control form-control-lg bg-light border-0" required placeholder="Nhập số điện thoại nhận hàng" value="<?= e($customer['phone'] ?? ($_SESSION['customer_phone'] ?? '')) ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold">ĐỊA CHỈ GIAO HÀNG *</label>
                                <textarea name="address" class="form-control form-control-lg bg-light border-0" rows="3" required placeholder="Nhập địa chỉ giao hàng chi tiết (Số nhà, đường, phường/xã, quận/huyện...)"><?= e($customer['address'] ?? '') ?></textarea>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-muted small fw-bold">GHI CHÚ (TÙY CHỌN)</label>
                                <textarea name="note" class="form-control form-control-lg bg-light border-0" rows="2" placeholder="Ghi chú thêm về đơn hàng (ví dụ: giao giờ hành chính, gọi trước khi giao)..."></textarea>
                            </div>

                            <h5 class="fw-bold mb-3 mt-5 font-serif text-forest">Phương thức thanh toán</h5>

                            <!-- COD -->
                            <div class="form-check p-3 border rounded-3 mb-2 bg-white payment-method-option" onclick="this.querySelector('input').checked = true;">
                                <input class="form-check-input ms-1" type="radio" name="payment_method" id="pay_cash" value="cash" checked>
                                <label class="form-check-label ms-2 d-flex align-items-center w-100 global-style-24b531" for="pay_cash">
                                    <i class="fa-solid fa-money-bill-wave text-success fs-4 me-3"></i>
                                    <div>
                                        <span class="d-block fw-bold text-dark">Thanh toán khi nhận hàng (COD)</span>
                                        <span class="d-block text-muted small">Thanh toán bằng tiền mặt khi giao hàng</span>
                                    </div>
                                </label>
                            </div>

                            <!-- Chuyển khoản ngân hàng -->
                            <div class="form-check p-3 border rounded-3 mb-2 bg-white payment-method-option" onclick="this.querySelector('input').checked = true;">
                                <input class="form-check-input ms-1" type="radio" name="payment_method" id="pay_transfer" value="bank_transfer">
                                <label class="form-check-label ms-2 d-flex align-items-center w-100 global-style-24b531" for="pay_transfer">
                                    <i class="fa-solid fa-building-columns text-primary fs-4 me-3"></i>
                                    <div>
                                        <span class="d-block fw-bold text-dark">Chuyển khoản ngân hàng</span>
                                        <span class="d-block text-muted small">Quét mã QR để thanh toán nhanh</span>
                                    </div>
                                </label>
                            </div>

                            <!-- Ví MoMo -->
                            <div class="form-check p-3 border rounded-3 mb-4 bg-white payment-method-option" onclick="this.querySelector('input').checked = true;">
                                <input class="form-check-input ms-1" type="radio" name="payment_method" id="pay_momo" value="momo">
                                <label class="form-check-label ms-2 d-flex align-items-center w-100 global-style-24b531" for="pay_momo">
                                    <i class="fa-solid fa-wallet text-danger fs-4 me-3"></i>
                                    <div>
                                        <span class="d-block fw-bold text-dark">Ví MoMo</span>
                                        <span class="d-block text-muted small">Thanh toán qua ví điện tử MoMo</span>
                                    </div>
                                </label>
                            </div>

                            <button type="submit" class="btn btn-caramel w-100 py-3 fw-bold rounded-pill text-white mt-3 shadow global-style-e7ec96">
                                ĐẶT HÀNG NGAY
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Cột tóm tắt đơn hàng -->
                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm rounded-4 position-sticky global-style-c9a35f" style="top: 100px;">
                        <div class="card-body p-4 p-md-5">
                            <h5 class="fw-bold mb-4 font-serif text-forest">Tóm tắt đơn hàng</h5>

                            <div class="mb-4">
                                <?php foreach ($cart_items as $item): 
                                    $itemPrice = (float)($item['total_price'] ?? ($item['unit_price'] ?? 0));
                                    $itemQty = (int)($item['quantity'] ?? 1);
                                    $itemName = $item['name'] ?? ($item['product_name'] ?? '');
                                ?>
                                    <div class="d-flex align-items-start mb-3 pb-3 border-bottom">
                                        <div class="position-relative me-3">
                                            <img src="<?= e(product_image($item['image'] ?? '')) ?>" alt="<?= e($itemName) ?>" class="rounded-3 object-fit-cover" width="60" height="60">
                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-caramel text-white">
                                                <?= $itemQty ?>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold text-dark mb-1 font-serif"><?= e($itemName) ?></h6>
                                            <div class="small text-muted mb-1 text-black">
                                                <?= !empty($item['size']) ? "Size " . e($item['size']) : (!empty($item['size_name']) ? "Size " . e($item['size_name']) : "") ?>
                                                <?= (!empty($item['temperature']) && $item['temperature'] === 'hot') ? ' · Nóng' : ' · Lạnh' ?>
                                                <?php if (!empty($item['toppings'])): ?>
                                                     · <?= count($item['toppings']) ?> Topping
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="fw-bold text-dark">
                                            <?= number_format($itemPrice * $itemQty, 0, ',', '.') ?>đ
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

                            <div class="text-center">
                                <a href="<?= e(url('cart')) ?>" class="text-muted small text-decoration-none hover-caramel transition-fast">
                                    <i class="fa-solid fa-arrow-left me-1"></i> Quay lại giỏ hàng
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php endif; ?>
