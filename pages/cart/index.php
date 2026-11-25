<!-- pages/cart/index.php — Giao diện Giỏ Hàng chuẩn gốc VAA THÉ -->
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

<main class="section-padding bg-ivory global-style-d0edae py-5">
    <div class="container">
        <div class="cart-header fade-up visible">
            <span class="small-label mb-2 d-inline-block">YOUR CART</span>
            <h2 class="font-serif fw-bold text-forest">Giỏ hàng</h2>
        </div>

        <div class="row g-5 fade-up visible">
            <!-- Cart Items -->
            <div class="col-lg-8">
                <div class="bg-white p-4 p-md-5 border global-style-38cfee" id="cart-container">
                    <?php if (empty($cart_items)): ?>
                        <div class="text-center py-5">
                            <i class="fa-solid fa-bag-shopping fa-4x text-muted mb-3 opacity-50"></i>
                            <p class="text-muted">Giỏ hàng của bạn đang trống.</p>
                            <a href="<?= e(url('products')) ?>" class="btn btn-caramel mt-3">TIẾP TỤC MUA SẮM</a>
                        </div>
                    <?php else: ?>
                        <?php foreach ($cart_items as $cart_id => $item): 
                            $itemPrice = (float)($item['total_price'] ?? ($item['unit_price'] ?? 0));
                            $itemQty = (int)($item['quantity'] ?? 1);
                            $itemName = $item['name'] ?? ($item['product_name'] ?? '');
                        ?>
                        <div class="cart-item d-flex align-items-start flex-wrap gap-4" id="cart-item-<?= e($cart_id) ?>">
                            <img src="<?= e(product_image($item['image'] ?? '')) ?>" alt="<?= e($itemName) ?>" width="100" height="100" class="object-fit-cover rounded">
                            <div class="flex-grow-1">
                                <h5 class="font-serif fw-bold mb-1 text-forest"><?= e($itemName) ?></h5>
                                
                                <div class="mb-2 small text-muted">
                                    <?php if (!empty($item['size'])): ?>
                                        <span class="badge bg-light text-dark border me-1">Size <?= e($item['size']) ?></span>
                                    <?php elseif (!empty($item['size_name'])): ?>
                                        <span class="badge bg-light text-dark border me-1">Size <?= e($item['size_name']) ?></span>
                                    <?php endif; ?>

                                    <?php if (!empty($item['temperature']) && $item['temperature'] === 'hot'): ?>
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 me-1"><i class="fa-solid me-1"></i>Nóng</span>
                                    <?php else: ?>
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 me-1"><i class="fa-solid me-1"></i>Lạnh</span>
                                        <?php if (isset($item['ice']) && $item['ice'] !== '' && $item['ice'] !== null): ?>
                                            <span class="badge bg-light text-dark border me-1">Đá <?= e($item['ice']) ?>%</span>
                                        <?php elseif (!empty($item['ice_name'])): ?>
                                            <span class="badge bg-light text-dark border me-1"><?= e($item['ice_name']) ?></span>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <?php if (isset($item['sugar']) && $item['sugar'] !== '' && $item['sugar'] !== null): ?>
                                        <span class="badge bg-light text-dark border me-1">Đường <?= e($item['sugar']) ?>%</span>
                                    <?php elseif (!empty($item['sugar_name'])): ?>
                                        <span class="badge bg-light text-dark border me-1"><?= e($item['sugar_name']) ?></span>
                                    <?php endif; ?>

                                    <?php if (!empty($item['toppings'])): ?>
                                        <?php foreach ($item['toppings'] as $t): ?>
                                            <span class="badge bg-light text-dark border me-1">+ <?= e(is_array($t) ? ($t['name'] ?? '') : $t) ?></span>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>

                                <p class="text-caramel fw-medium mb-3"><?= number_format($itemPrice, 0, ',', '.') ?>đ</p>
                                
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center border border-sage rounded overflow-hidden">
                                        <button class="btn qty-btn border-0" type="button" onclick="updateCartItem('<?= e($cart_id) ?>', -1)"><i class="fa-solid fa-minus fs-6"></i></button>
                                        <input type="text" class="qty-input" value="<?= $itemQty ?>" readonly id="qty-<?= e($cart_id) ?>">
                                        <button class="btn qty-btn border-0" type="button" onclick="updateCartItem('<?= e($cart_id) ?>', 1)"><i class="fa-solid fa-plus fs-6"></i></button>
                                    </div>
                                    <button class="btn btn-link text-danger text-decoration-none ms-4 small" type="button" onclick="removeCartItem('<?= e($cart_id) ?>')"><i class="fa-regular fa-trash-can me-1"></i> Xóa</button>
                                </div>
                            </div>
                            <div class="text-end fw-bold text-dark fs-5">
                                <?= number_format($itemPrice * $itemQty, 0, ',', '.') ?>đ
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="summary-card position-sticky global-style-c9a35f" style="top: 100px;">
                    <h4 class="font-serif fw-bold mb-4 pb-3 border-bottom text-forest">Tóm tắt đơn hàng</h4>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Tạm tính</span>
                        <span class="fw-medium"><?= number_format($subtotal, 0, ',', '.') ?>đ</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4 pb-4 border-bottom">
                        <span class="text-muted">Phí giao hàng</span>
                        <span class="fw-medium"><?= number_format($shipping, 0, ',', '.') ?>đ</span>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-5">
                        <span class="fw-bold fs-5 text-forest">Tổng cộng</span>
                        <span class="fw-bold text-caramel fs-3"><?= number_format($total, 0, ',', '.') ?>đ</span>
                    </div>
                    
                    <?php if (!empty($cart_items)): ?>
                    <a href="<?= e(url('checkout')) ?>" class="btn btn-caramel w-100 py-3 fw-bold tracking-wide">TIẾN HÀNH THANH TOÁN</a>
                    <?php else: ?>
                    <button class="btn btn-caramel w-100 py-3 fw-bold tracking-wide opacity-50" disabled>TIẾN HÀNH THANH TOÁN</button>
                    <?php endif; ?>

                    <div class="text-center mt-3">
                        <a href="<?= e(url('products')) ?>" class="text-muted small text-decoration-none hover-caramel transition-fast"><i class="fa-solid fa-arrow-left me-1"></i> Tiếp tục mua sắm</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
