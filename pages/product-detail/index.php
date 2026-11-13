<!-- pages/product-detail/index.php — Chi tiết sản phẩm VAA THÉ -->
<section class="product-detail-section py-5">
    <div class="container">
        
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= e(url('home')) ?>" class="text-decoration-none text-muted">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="<?= e(url('products')) ?>" class="text-decoration-none text-muted">Sản phẩm</a></li>
                <li class="breadcrumb-item active text-forest fw-semibold" aria-current="page"><?= e($product['name']) ?></li>
            </ol>
        </nav>

        <div class="row g-5">
            <!-- Cột ảnh bên trái -->
            <div class="col-lg-6">
                <div class="product-detail-image-box position-relative bg-light rounded-4 p-5 d-flex align-items-center justify-content-center shadow-sm">
                    <img src="<?= e(product_image($product['image'])) ?>" alt="<?= e($product['name']) ?>" class="img-fluid drop-shadow" style="max-height: 420px; object-fit: contain;">
                </div>
            </div>

            <!-- Cột thông tin & Tuỳ chọn bên phải -->
            <div class="col-lg-6">
                <form id="orderForm" action="<?= e(url('cart', ['action' => 'add'])) ?>" method="POST">
                    <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">

                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <span class="badge bg-sage text-forest mb-2 px-3 py-2 rounded-pill"><?= e($product['category_name']) ?></span>
                            <h1 class="font-serif fw-bold text-forest mb-0"><?= e($product['name']) ?></h1>
                        </div>
                        <button type="button" class="btn btn-outline-danger rounded-circle p-2" id="btnToggleFavorite" onclick="toggleFavoriteDetail(<?= (int)$product['id'] ?>)">
                            <i class="<?= $isFavorited ? 'fa-solid' : 'fa-regular' ?> fa-heart fs-5"></i>
                        </button>
                    </div>

                    <div class="price-box my-3">
                        <span class="fs-2 fw-bold text-caramel" id="totalPrice" data-base-price="<?= (float)$product['price'] ?>">
                            <?= money($product['price']) ?>
                        </span>
                    </div>

                    <p class="text-muted mb-4"><?= nl2br(e($product['description'] ?? '')) ?></p>

                    <!-- Chọn Size -->
                    <?php if (!empty($sizes)): ?>
                    <div class="option-group mb-4">
                        <label class="fw-bold text-forest mb-2">Chọn kích cỡ (Size):</label>
                        <div class="d-flex gap-2 flex-wrap">
                            <?php foreach ($sizes as $idx => $sz): ?>
                            <div class="form-check custom-radio-btn p-0">
                                <input type="radio" class="btn-check" name="size_id" id="size_<?= $sz['id'] ?>" value="<?= $sz['id'] ?>" data-price="<?= (float)$sz['price_modifier'] ?>" <?= $idx === 0 ? 'checked' : '' ?>>
                                <label class="btn btn-outline-forest rounded-pill px-4 py-2" for="size_<?= $sz['id'] ?>">
                                    <?= e($sz['name']) ?> <?= $sz['price_modifier'] > 0 ? '(+' . money($sz['price_modifier']) . ')' : '' ?>
                                </label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Mức đường -->
                    <?php if (!empty($sugarLevels)): ?>
                    <div class="option-group mb-4">
                        <label class="fw-bold text-forest mb-2">Độ ngọt (Đường):</label>
                        <div class="d-flex gap-2 flex-wrap">
                            <?php foreach ($sugarLevels as $idx => $sug): ?>
                            <div class="form-check custom-radio-btn p-0">
                                <input type="radio" class="btn-check" name="sugar_id" id="sug_<?= $sug['id'] ?>" value="<?= $sug['id'] ?>" <?= $idx === 2 ? 'checked' : ($idx === 0 && count($sugarLevels) <= 2 ? 'checked' : '') ?>>
                                <label class="btn btn-outline-secondary rounded-pill px-3 py-1" for="sug_<?= $sug['id'] ?>">
                                    <?= e($sug['name']) ?>
                                </label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Mức đá -->
                    <?php if (!empty($iceLevels)): ?>
                    <div class="option-group mb-4">
                        <label class="fw-bold text-forest mb-2">Lượng đá:</label>
                        <div class="d-flex gap-2 flex-wrap">
                            <?php foreach ($iceLevels as $idx => $ice): ?>
                            <div class="form-check custom-radio-btn p-0">
                                <input type="radio" class="btn-check" name="ice_id" id="ice_<?= $ice['id'] ?>" value="<?= $ice['id'] ?>" <?= $idx === 2 ? 'checked' : ($idx === 0 && count($iceLevels) <= 2 ? 'checked' : '') ?>>
                                <label class="btn btn-outline-secondary rounded-pill px-3 py-1" for="ice_<?= $ice['id'] ?>">
                                    <?= e($ice['name']) ?>
                                </label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Chọn Topping -->
                    <?php if (!empty($toppings)): ?>
                    <div class="option-group mb-4">
                        <label class="fw-bold text-forest mb-2">Thêm Topping:</label>
                        <div class="row g-2">
                            <?php foreach ($toppings as $top): ?>
                            <div class="col-6">
                                <div class="form-check custom-checkbox p-2 border rounded-3 d-flex justify-content-between align-items-center">
                                    <div>
                                        <input class="form-check-input ms-0 me-2 topping-check" type="checkbox" name="toppings[]" value="<?= $top['id'] ?>" id="top_<?= $top['id'] ?>" data-price="<?= (float)$top['price'] ?>">
                                        <label class="form-check-label small" for="top_<?= $top['id'] ?>"><?= e($top['name']) ?></label>
                                    </div>
                                    <span class="text-caramel small fw-semibold">+<?= money($top['price']) ?></span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Số lượng & Nút đặt mua -->
                    <div class="d-flex align-items-center gap-3 pt-3 border-top">
                        <div class="input-group quantity-box" style="width: 130px;">
                            <button class="btn btn-outline-secondary" type="button" onclick="adjustQty(-1)">-</button>
                            <input type="number" name="quantity" id="quantityInput" class="form-control text-center" value="1" min="1" max="99">
                            <button class="btn btn-outline-secondary" type="button" onclick="adjustQty(1)">+</button>
                        </div>
                        <button type="submit" class="btn btn-forest flex-grow-1 py-3 rounded-pill fw-bold">
                            <i class="fa-solid fa-bag-shopping me-2"></i> Thêm Vào Giỏ Hàng
                        </button>
                    </div>

                </form>
            </div>
        </div>

        <!-- Sản phẩm tương tự -->
        <?php if (!empty($relatedProducts)): ?>
        <div class="related-products mt-5 pt-5 border-top">
            <h3 class="font-serif fw-bold text-forest mb-4">Sản Phẩm Cùng Loại</h3>
            <div class="row g-4">
                <?php foreach ($relatedProducts as $rel): ?>
                <div class="col-6 col-md-3">
                    <div class="product-card h-100 d-flex flex-column border-0 shadow-sm">
                        <div class="product-image-box position-relative">
                            <a href="<?= e(url('product-detail', ['id' => $rel['id']])) ?>">
                                <img src="<?= e(product_image($rel['image'])) ?>" alt="<?= e($rel['name']) ?>" class="img-fluid drop-shadow">
                            </a>
                        </div>
                        <div class="p-3 bg-white d-flex flex-column flex-grow-1 text-center">
                            <h6 class="font-serif fw-bold mb-1"><?= e($rel['name']) ?></h6>
                            <p class="text-caramel fw-bold mt-auto mb-2"><?= money($rel['price']) ?></p>
                            <a href="<?= e(url('product-detail', ['id' => $rel['id']])) ?>" class="btn btn-sm btn-outline-forest rounded-pill">Chi tiết</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

    </div>
</section>

<script>
function adjustQty(delta) {
    const input = document.getElementById('quantityInput');
    let val = parseInt(input.value) || 1;
    val += delta;
    if (val < 1) val = 1;
    input.value = val;
    calculateTotal();
}

function calculateTotal() {
    const basePrice = parseFloat(document.getElementById('totalPrice').dataset.basePrice) || 0;
    let sizePrice = 0;
    const selectedSize = document.querySelector('input[name="size_id"]:checked');
    if (selectedSize) {
        sizePrice = parseFloat(selectedSize.dataset.price) || 0;
    }

    let toppingsPrice = 0;
    document.querySelectorAll('.topping-check:checked').forEach(cb => {
        toppingsPrice += parseFloat(cb.dataset.price) || 0;
    });

    const qty = parseInt(document.getElementById('quantityInput').value) || 1;
    const unitPrice = basePrice + sizePrice + toppingsPrice;
    const total = unitPrice * qty;

    document.getElementById('totalPrice').innerText = new Intl.NumberFormat('vi-VN').format(total) + 'đ';
}

document.querySelectorAll('input[name="size_id"], .topping-check').forEach(el => {
    el.addEventListener('change', calculateTotal);
});
document.getElementById('quantityInput')?.addEventListener('input', calculateTotal);

function toggleFavoriteDetail(productId) {
    fetch('<?= e(url('yeuthich', ['action' => 'toggle'])) ?>', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: new URLSearchParams({product_id: productId})
    })
    .then(r => r.json())
    .then(data => {
        if (data.status === 'unauthorized') {
            window.location.href = '<?= e(url('login')) ?>';
            return;
        }
        if (data.status === 'success') {
            const icon = document.querySelector('#btnToggleFavorite i');
            if (data.action === 'added') {
                icon.classList.remove('fa-regular');
                icon.classList.add('fa-solid');
            } else {
                icon.classList.remove('fa-solid');
                icon.classList.add('fa-regular');
            }
        }
    });
}
</script>

