<!-- pages/checkout/index.php — Giao diện Thanh Toán & Xác Nhận Đơn -->
<section class="checkout-section py-5">
    <div class="container">

        <?php if ($isSuccess && $successOrderId): ?>
            <div class="text-center py-5">
                <div class="card border-0 shadow-sm rounded-4 p-5 mx-auto" style="max-width: 600px;">
                    <i class="fa-solid fa-circle-check text-success fa-4x mb-3"></i>
                    <h2 class="font-serif fw-bold text-forest mb-2">Đặt Hàng Thành Công!</h2>
                    <p class="text-muted mb-4">Cảm ơn bạn đã lựa chọn VAA THÉ. Mã đơn hàng của bạn là <strong>#<?= (int)$successOrderId ?></strong>.</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="<?= e(url('home')) ?>" class="btn btn-outline-forest rounded-pill px-4">Về trang chủ</a>
                        <a href="<?= e(url('products')) ?>" class="btn btn-forest rounded-pill px-4">Tiếp tục mua hàng</a>
                    </div>
                </div>
            </div>
        <?php else: ?>

            <h1 class="font-serif display-5 fw-bold text-forest mb-4">Xác Nhận & Đặt Hàng</h1>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger rounded-3 mb-4">
                    <ul class="mb-0">
                        <?php foreach ($errors as $err): ?>
                            <li><?= e($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?= e(url('checkout')) ?>" method="POST">
                <div class="row g-5">
                    <!-- Thông tin giao hàng -->
                    <div class="col-lg-7">
                        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                            <h4 class="font-serif fw-bold text-forest mb-3">Thông Tin Người Nhận</h4>
                            
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Họ và tên *</label>
                                <input type="text" name="full_name" class="form-control" required value="<?= e($_SESSION['customer_name'] ?? '') ?>" placeholder="Nguyễn Văn A">
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Số điện thoại *</label>
                                <input type="tel" name="phone" class="form-control" required value="<?= e($_SESSION['customer_phone'] ?? '') ?>" placeholder="0901234567">
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Hình thức nhận hàng</label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="order_type" id="type_takeaway" value="takeaway" checked onchange="toggleAddressField(false)">
                                        <label class="form-check-label" for="type_takeaway">Đến lấy tại quán</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="order_type" id="type_delivery" value="delivery" onchange="toggleAddressField(true)">
                                        <label class="form-check-label" for="type_delivery">Giao tận nơi</label>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3" id="addressGroup" style="display: none;">
                                <label class="form-label small fw-bold text-muted">Địa chỉ nhận hàng *</label>
                                <textarea name="address" class="form-control" rows="2" placeholder="Số nhà, đường, phường/xã, quận/huyện..."></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Ghi chú đơn hàng</label>
                                <textarea name="note" class="form-control" rows="2" placeholder="Ví dụ: Giao giờ hành chính, gọi trước khi giao..."></textarea>
                            </div>
                        </div>

                        <!-- Phương thức thanh toán -->
                        <div class="card border-0 shadow-sm rounded-4 p-4">
                            <h4 class="font-serif fw-bold text-forest mb-3">Phương Thức Thanh Toán</h4>
                            <div class="form-check p-3 border rounded-3 mb-2 d-flex align-items-center">
                                <input class="form-check-input ms-0 me-3" type="radio" name="payment_method" id="pay_cod" value="cod" checked>
                                <label class="form-check-label flex-grow-1" for="pay_cod">
                                    <strong>Thanh toán tiền mặt khi nhận hàng (COD)</strong>
                                    <div class="text-muted small">Thanh toán trực tiếp cho nhân viên khi nhận trà</div>
                                </label>
                            </div>
                            <div class="form-check p-3 border rounded-3 d-flex align-items-center">
                                <input class="form-check-input ms-0 me-3" type="radio" name="payment_method" id="pay_momo" value="momo">
                                <label class="form-check-label flex-grow-1" for="pay_momo">
                                    <strong>Ví Điện Tử MoMo / QR Code</strong>
                                    <div class="text-muted small">Quét mã QR thanh toán nhanh qua ứng dụng MoMo</div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Tóm tắt đơn hàng -->
                    <div class="col-lg-5">
                        <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 100px;">
                            <h4 class="font-serif fw-bold text-forest mb-3">Đơn Hàng (<?= count($cartItems) ?> món)</h4>
                            
                            <div class="checkout-items-list mb-3" style="max-height: 280px; overflow-y: auto;">
                                <?php foreach ($cartItems as $item): ?>
                                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="<?= e(product_image($item['image'])) ?>" alt="" class="rounded-2" style="width: 45px; height: 45px; object-fit: cover;">
                                        <div>
                                            <div class="fw-semibold small text-forest"><?= e($item['product_name']) ?></div>
                                            <div class="text-muted" style="font-size: 0.75rem;">x<?= (int)$item['quantity'] ?> · <?= e($item['size_name'] ?? '') ?></div>
                                        </div>
                                    </div>
                                    <div class="fw-bold text-caramel small"><?= money($item['unit_price'] * $item['quantity']) ?></div>
                                </div>
                                <?php endforeach; ?>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Tạm tính</span>
                                <span class="fw-semibold"><?= money($totalCartAmount) ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Vận chuyển</span>
                                <span class="text-success fw-semibold">0đ</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-4 fs-5">
                                <span class="fw-bold text-forest">Tổng tiền</span>
                                <span class="fw-bold text-caramel"><?= money($totalCartAmount) ?></span>
                            </div>

                            <button type="submit" class="btn btn-forest w-100 py-3 rounded-pill fw-bold fs-6">
                                <i class="fa-solid fa-check me-2"></i> Xác Nhận Đặt Hàng
                            </button>
                        </div>
                    </div>
                </div>
            </form>

        <?php endif; ?>

    </div>
</section>

<script>
function toggleAddressField(show) {
    document.getElementById('addressGroup').style.display = show ? 'block' : 'none';
}
</script>

