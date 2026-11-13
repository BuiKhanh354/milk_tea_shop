<!-- pages/cart/index.php — Giao diện Giỏ Hàng -->
<section class="cart-section py-5">
    <div class="container">
        
        <h1 class="font-serif display-5 fw-bold text-forest mb-4">Giỏ Hàng Của Bạn</h1>

        <?php if (!empty($cartItems)): ?>
        <div class="row g-5">
            <!-- Danh sách sản phẩm trong giỏ -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="text-muted small border-bottom">
                                <tr>
                                    <th scope="col" style="min-width: 250px;">Sản phẩm</th>
                                    <th scope="col" class="text-center">Đơn giá</th>
                                    <th scope="col" class="text-center" style="width: 140px;">Số lượng</th>
                                    <th scope="col" class="text-end">Tạm tính</th>
                                    <th scope="col" class="text-center" style="width: 50px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cartItems as $item): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="<?= e(product_image($item['image'])) ?>" alt="<?= e($item['product_name']) ?>" class="rounded-3 shadow-xs" style="width: 65px; height: 65px; object-fit: cover;">
                                            <div>
                                                <h6 class="font-serif fw-bold mb-1 text-forest"><?= e($item['product_name']) ?></h6>
                                                <div class="small text-muted">
                                                    <?php if (!empty($item['size_name'])): ?>
                                                        <span>Size: <?= e($item['size_name']) ?></span>
                                                    <?php endif; ?>
                                                    <?php if (!empty($item['sugar_name'])): ?>
                                                        <span> · <?= e($item['sugar_name']) ?></span>
                                                    <?php endif; ?>
                                                    <?php if (!empty($item['ice_name'])): ?>
                                                        <span> · <?= e($item['ice_name']) ?></span>
                                                    <?php endif; ?>
                                                </div>
                                                <?php if (!empty($item['toppings'])): ?>
                                                    <div class="small text-caramel">
                                                        Topping: <?= e(implode(', ', array_column($item['toppings'], 'name'))) ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center fw-semibold"><?= money($item['unit_price']) ?></td>
                                    <td class="text-center">
                                        <form action="<?= e(url('cart', ['action' => 'update', 'item_key' => $item['item_key']])) ?>" method="POST" class="d-inline-flex">
                                            <input type="number" name="quantity" value="<?= (int)$item['quantity'] ?>" min="1" max="99" class="form-control form-control-sm text-center" style="width: 70px;" onchange="this.form.submit()">
                                        </form>
                                    </td>
                                    <td class="text-end fw-bold text-caramel"><?= money($item['unit_price'] * $item['quantity']) ?></td>
                                    <td class="text-center">
                                        <a href="<?= e(url('cart', ['action' => 'remove', 'item_key' => $item['item_key']])) ?>" class="text-danger p-2" title="Xoá món" onclick="return confirm('Bạn có chắc muốn xoá món này?')">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                        <a href="<?= e(url('products')) ?>" class="btn btn-outline-forest rounded-pill px-4">
                            <i class="fa-solid fa-arrow-left me-2"></i> Tiếp tục chọn món
                        </a>
                        <a href="<?= e(url('cart', ['action' => 'clear'])) ?>" class="btn btn-link text-muted text-decoration-none small" onclick="return confirm('Xoá toàn bộ giỏ hàng?')">
                            <i class="fa-solid fa-broom me-1"></i> Xoá hết giỏ hàng
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tóm tắt đơn hàng -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 100px;">
                    <h4 class="font-serif fw-bold text-forest mb-4">Tổng Đơn Hàng</h4>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Tổng phụ</span>
                        <span class="fw-semibold"><?= money($totalCartAmount) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Phí giao hàng</span>
                        <span class="text-success fw-semibold">Miễn phí</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4 fs-5">
                        <span class="fw-bold text-forest">Tổng thanh toán</span>
                        <span class="fw-bold text-caramel"><?= money($totalCartAmount) ?></span>
                    </div>

                    <a href="<?= e(url('checkout')) ?>" class="btn btn-forest w-100 py-3 rounded-pill fw-bold fs-6">
                        Tiến Hành Thanh Toán <i class="fa-solid fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="text-center py-5">
            <div class="empty-cart-state py-5">
                <i class="fa-solid fa-bag-shopping fa-4x text-muted mb-3 opacity-50"></i>
                <h3 class="font-serif text-forest mb-2">Giỏ hàng của bạn đang trống</h3>
                <p class="text-muted mb-4">Hãy dạo một vòng menu và chọn những thức uống bạn yêu thích nhé!</p>
                <a href="<?= e(url('products')) ?>" class="btn btn-forest px-4 py-2 rounded-pill fw-semibold">
                    Khám Phá Menu Ngay
                </a>
            </div>
        </div>
        <?php endif; ?>

    </div>
</section>

