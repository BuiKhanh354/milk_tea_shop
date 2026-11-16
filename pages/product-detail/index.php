<!-- Breadcrumb -->
<div class="container page-offset mb-3 fade-up visible">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb vaa-breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="<?= e(url('home')) ?>">HOME</a></li>
            <li class="breadcrumb-item"><a href="<?= e(url('products')) ?>">MENU</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= mb_strtoupper($product['name']) ?></li>
        </ol>
    </nav>
</div>

<!-- Product Detail Section -->
<main class="container mb-5 fade-up visible">
    <div class="row g-5">
        <!-- Left: Product Image -->
        <div class="col-lg-6">
            <div class="product-image-container sticky-top product-detail-style-c9a35f">
                <img src="<?= e(product_image($product['image'])) ?>" alt="<?= e($product['name']) ?>" class="img-fluid" id="product-img-main">
            </div>
        </div>
        
        <!-- Right: Product Info & Options -->
        <div class="col-lg-6">
            <span class="product-category"><?= e($product['category_name']) ?></span>
            <h1 class="product-title"><?= e($product['name']) ?></h1>
            
            <div class="product-rating">
                <span class="stars">
                    <?php 
                        $fullStars = floor($product['rating']);
                        $halfStar = ($product['rating'] - $fullStars) >= 0.5;
                        for($i=1; $i<=5; $i++) {
                            if($i <= $fullStars) echo '<i class="fa-solid fa-star"></i>';
                            elseif($i == $fullStars + 1 && $halfStar) echo '<i class="fa-solid fa-star-half-stroke"></i>';
                            else echo '<i class="fa-regular fa-star"></i>';
                        }
                    ?>
                </span>
                <span class="fw-bold text-dark"><?= number_format($product['rating'], 1) ?></span>
                <span class="text-muted">(<?= $product['review_count'] ?> reviews)</span>
            </div>
            
            <p class="product-description"><?= nl2br(e($product['description'] ?? '')) ?></p>
            
            <div class="d-flex align-items-center justify-content-between gap-3 mb-4 pb-4 border-bottom border-sage border-opacity-25 flex-wrap">
                <div class="d-flex align-items-center gap-3">
                    <div id="base-price" data-price="<?= $product['price'] ?>" class="product-price">
                        <?= number_format($product['price'], 0, ',', '.') ?>đ
                    </div>
                    <span class="badge bg-light text-dark border px-3 py-2 rounded-pill small fw-medium">
                        <i class="fa-solid fa-circle-check text-success me-1"></i> Có sẵn
                    </span>
                </div>
            </div>

            <!-- Form Tuỳ chọn -->
            <form action="<?= e(url('cart', ['action' => 'add'])) ?>" method="POST" id="order-form">
                <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">

                <!-- Sizes -->
                <?php if (!empty($sizes)): ?>
                <div class="mb-4">
                    <label class="form-label d-block text-forest fw-bold mb-2">1. Chọn Size</label>
                    <div class="row g-2">
                        <?php foreach($sizes as $index => $size): ?>
                        <div class="col-4">
                            <input type="radio" class="btn-check size-radio" name="size_id" id="size-<?= $size['id'] ?>" value="<?= $size['id'] ?>" data-extra="<?= (float)$size['price_modifier'] ?>" <?= $index === 0 ? 'checked' : '' ?> required>
                            <label class="btn btn-outline-forest w-100 py-2 d-flex flex-column align-items-center" for="size-<?= $size['id'] ?>">
                                <span class="fw-bold"><?= e($size['name']) ?></span>
                                <span class="small text-muted"><?= $size['price_modifier'] > 0 ? '+' . number_format($size['price_modifier'], 0, ',', '.') . 'đ' : 'Gốc' ?></span>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Sugar -->
                <?php if (!empty($sugarLevels)): ?>
                <div class="mb-4">
                    <label class="form-label d-block text-forest fw-bold mb-2">2. Lượng Đường</label>
                    <div class="row g-2">
                        <?php foreach($sugarLevels as $index => $sugar): ?>
                        <div class="col">
                            <input type="radio" class="btn-check" name="sugar_id" id="sugar-<?= $sugar['id'] ?>" value="<?= $sugar['id'] ?>" <?= $index === 2 ? 'checked' : ($index === 0 ? 'checked' : '') ?>>
                            <label class="btn btn-outline-forest w-100 py-2 px-1 text-center small" for="sugar-<?= $sugar['id'] ?>">
                                <?= e($sugar['name']) ?>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Ice -->
                <?php if (!empty($iceLevels)): ?>
                <div class="mb-4">
                    <label class="form-label d-block text-forest fw-bold mb-2">3. Lượng Đá</label>
                    <div class="row g-2">
                        <?php foreach($iceLevels as $index => $ice): ?>
                        <div class="col">
                            <input type="radio" class="btn-check" name="ice_id" id="ice-<?= $ice['id'] ?>" value="<?= $ice['id'] ?>" <?= $index === 2 ? 'checked' : ($index === 0 ? 'checked' : '') ?>>
                            <label class="btn btn-outline-forest w-100 py-2 px-1 text-center small" for="ice-<?= $ice['id'] ?>">
                                <?= e($ice['name']) ?>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Toppings -->
                <?php if (!empty($toppings)): ?>
                <div class="mb-4">
                    <label class="form-label d-block text-forest fw-bold mb-2">4. Topping Thêm</label>
                    <div class="row g-2">
                        <?php foreach($toppings as $topping): ?>
                        <div class="col-6">
                            <div class="topping-item border rounded-3 p-2 d-flex justify-content-between align-items-center">
                                <div class="form-check mb-0">
                                    <input class="form-check-input topping-checkbox" type="checkbox" name="toppings[]" value="<?= $topping['id'] ?>" id="topping-<?= $topping['id'] ?>" data-price="<?= (float)$topping['price'] ?>">
                                    <label class="form-check-label small ms-1" for="topping-<?= $topping['id'] ?>">
                                        <?= e($topping['name']) ?>
                                    </label>
                                </div>
                                <span class="small text-caramel fw-bold">+<?= number_format($topping['price'], 0, ',', '.') ?>đ</span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Quantity & Actions -->
                <div class="d-flex align-items-center gap-3 pt-3">
                    <div class="input-group quantity-box" style="width: 130px;">
                        <button class="btn btn-outline-secondary" type="button" onclick="adjustQty(-1)">-</button>
                        <input type="number" name="quantity" id="quantityInput" class="form-control text-center" value="1" min="1" max="99">
                        <button class="btn btn-outline-secondary" type="button" onclick="adjustQty(1)">+</button>
                    </div>

                    <button type="submit" class="btn btn-forest flex-grow-1 py-3 rounded-pill fw-bold" id="add-to-cart-btn">
                        <i class="fa-solid fa-bag-shopping me-2"></i> Thêm vào giỏ (<span id="total-price"><?= number_format($product['price'], 0, ',', '.') ?>đ</span>)
                    </button>
                </div>
            </form>

        </div>
    </div>
</main>

<!-- Customer Reviews Section -->
<section class="container py-5 fade-up visible">
    <div class="text-center mb-5">
        <h2 class="font-serif fw-bold text-forest">Đánh giá của khách hàng</h2>
        <div class="mt-4">
            <h1 class="font-serif text-caramel mb-0" style="font-size: 3rem;"><?= number_format($product['rating'], 1) ?></h1>
            <div class="fs-4 stars my-2">
                <?php 
                    for($i=1; $i<=5; $i++) {
                        if($i <= $fullStars) echo '<i class="fa-solid fa-star text-caramel"></i>';
                        elseif($i == $fullStars + 1 && $halfStar) echo '<i class="fa-solid fa-star-half-stroke text-caramel"></i>';
                        else echo '<i class="fa-regular fa-star text-caramel"></i>';
                    }
                ?>
            </div>
            <p class="text-muted"><?= (int)$product['review_count'] ?> lượt đánh giá</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex flex-column gap-4">
                <?php foreach($reviews as $rev): ?>
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold text-forest"><?= e($rev['name']) ?></span>
                        <span class="text-muted small"><?= e($rev['date']) ?></span>
                    </div>
                    <div class="stars small mb-2 text-caramel">
                        <?php for($s=0; $s<$rev['rating']; $s++) echo '<i class="fa-solid fa-star me-1"></i>'; ?>
                    </div>
                    <p class="mb-0 text-muted">"<?= e($rev['comment']) ?>"</p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- Related Products -->
<?php if (!empty($relatedProducts)): ?>
<section class="container py-5 border-top border-sage border-opacity-25 fade-up visible">
    <div class="text-center mb-5">
        <h2 class="font-serif fw-bold text-forest">Sản phẩm liên quan</h2>
    </div>
    
    <div class="row g-4">
        <?php foreach($relatedProducts as $item): ?>
        <div class="col-6 col-md-3">
            <div class="card product-card h-100 text-center p-2 p-md-3 border-0 shadow-sm">
                <a href="<?= e(url('product-detail', ['id' => (int)$item['id']])) ?>" class="text-decoration-none">
                    <img src="<?= e(product_image($item['image'])) ?>" class="card-img-top mb-3" alt="<?= e($item['name']) ?>">
                    <h5 class="product-card-title"><?= e($item['name']) ?></h5>
                    <p class="text-caramel fw-bold mb-0"><?= number_format($item['price'], 0, ',', '.') ?>đ</p>
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<script>
function adjustQty(delta) {
    const input = document.getElementById('quantityInput');
    let val = parseInt(input.value) || 1;
    val += delta;
    if (val < 1) val = 1;
    input.value = val;
    calculateDetailTotal();
}

function calculateDetailTotal() {
    const basePrice = parseFloat(document.getElementById('base-price').dataset.price) || 0;
    let sizeExtra = 0;
    const checkedSize = document.querySelector('.size-radio:checked');
    if (checkedSize) {
        sizeExtra = parseFloat(checkedSize.dataset.extra) || 0;
    }

    let toppingsTotal = 0;
    document.querySelectorAll('.topping-checkbox:checked').forEach(cb => {
        toppingsTotal += parseFloat(cb.dataset.price) || 0;
    });

    const qty = parseInt(document.getElementById('quantityInput').value) || 1;
    const finalTotal = (basePrice + sizeExtra + toppingsTotal) * qty;

    document.getElementById('total-price').innerText = new Intl.NumberFormat('vi-VN').format(finalTotal) + 'đ';
}

document.querySelectorAll('.size-radio, .topping-checkbox').forEach(el => {
    el.addEventListener('change', calculateDetailTotal);
});
document.getElementById('quantityInput')?.addEventListener('input', calculateDetailTotal);
</script>
