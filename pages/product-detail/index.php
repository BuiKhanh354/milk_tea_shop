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
            <span class="product-category"><?= e($product['category_name'] ?? 'VAA THÉ') ?></span>
            <h1 class="product-title"><?= e($product['name']) ?></h1>
            
            <div class="product-rating">
                <span class="stars">
                    <?php 
                        $fullStars = floor($product['rating']);
                        $halfStar = ($product['rating'] - $fullStars) >= 0.5;
                        for($i=1; $i<=5; $i++) {
                            if($i <= $fullStars) echo '<i class="fa-solid fa-star text-caramel"></i>';
                            elseif($i == $fullStars + 1 && $halfStar) echo '<i class="fa-solid fa-star-half-stroke text-caramel"></i>';
                            else echo '<i class="fa-regular fa-star text-caramel"></i>';
                        }
                    ?>
                </span>
                <span class="fw-bold text-dark"><?= number_format($product['rating'], 1) ?></span>
                <span class="text-muted">(<?= (int)$product['review_count'] ?> reviews)</span>
            </div>
            
            <p class="product-description"><?= nl2br(e($product['description'] ?? '')) ?></p>
            
            <div class="d-flex align-items-center justify-content-between gap-3 mb-4 pb-4 border-bottom border-sage border-opacity-25 flex-wrap">
                <div class="d-flex align-items-center gap-3">
                    <div id="base-price" data-price="<?= (float)$product['price'] ?>" class="product-price">
                        <?= number_format($product['price'], 0, ',', '.') ?>đ
                    </div>
                    <!-- Tình trạng còn hàng dựa vào công thức định lượng nguyên liệu -->
                    <div id="stock-badge-container">
                        <?php 
                        $defaultStock = $stockInfo['available_quantity'] ?? null;
                        $hasRecipe = $stockInfo['has_recipe'] ?? false;
                        $firstSize = !empty($sizes) ? $sizes[0] : null;
                        if ($firstSize && isset($stockInfo['by_size'][$firstSize['id']])) {
                            $defaultStock = $stockInfo['by_size'][$firstSize['id']]['available_quantity'];
                        }
                        ?>
                        <?php if (!$hasRecipe): ?>
                            <span class="badge bg-light text-dark border px-3 py-2 rounded-pill small fw-medium">
                                <i class="fa-solid fa-circle-check text-success me-1"></i> Có sẵn
                            </span>
                        <?php elseif ($defaultStock <= 0): ?>
                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2 rounded-pill small fw-bold">
                                <i class="fa-solid fa-ban me-1"></i> Tạm hết hàng
                            </span>
                        <?php elseif ($defaultStock <= 10): ?>
                            <span class="badge bg-warning bg-opacity-15 text-dark border border-warning border-opacity-50 px-3 py-2 rounded-pill small fw-bold">
                                <i class="fa-solid fa-fire me-1 text-warning"></i> Sắp hết (Còn <?= $defaultStock ?> ly)
                            </span>
                        <?php else: ?>
                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill small fw-bold">
                                <i class="fa-solid fa-circle-check me-1"></i> Còn <?= $defaultStock ?> ly
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <button type="button" class="btn btn-favorite <?= isset($isFavorited) && $isFavorited ? 'active' : '' ?>" id="btn-favorite" aria-label="Thêm hoặc bỏ yêu thích <?= e($product['name']) ?>" data-product-id="<?= (int)$product['id'] ?>">
                    <i class="<?= isset($isFavorited) && $isFavorited ? 'fa-solid text-danger' : 'fa-regular' ?> fa-heart fs-4"></i>
                </button>
            </div>

            <!-- Form Options -->
            <form id="product-options-form">
                <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
                
                <!-- Size -->
                <div class="mb-4">
                    <span class="option-label">CHỌN KÍCH CỠ</span>
                    <div class="vaa-radio-group">
                        <?php foreach ($sizes as $index => $size): 
                            $sId = $size['id'];
                            $sStock = $hasRecipe ? ($stockInfo['by_size'][$sId]['available_quantity'] ?? 0) : null;
                        ?>
                        <div class="vaa-radio-btn">
                            <input type="radio" name="size" id="size-<?= strtolower($size['name']) ?>" 
                                   value="<?= e($size['name']) ?>" 
                                   data-price="<?= (float)$size['extra_price'] ?>"
                                   data-size-id="<?= $size['id'] ?>"
                                   data-stock="<?= $sStock !== null ? $sStock : '' ?>"
                                   <?= $index === 0 ? 'checked' : '' ?>>
                            <label for="size-<?= strtolower($size['name']) ?>">
                                <?= e($size['name']) ?> (+<?= number_format($size['extra_price'], 0, ',', '.') ?>đ)
                                <?php if ($hasRecipe && $sStock !== null): ?>
                                    <span class="small ms-1 <?= $sStock <= 0 ? 'text-danger fw-bold' : ($sStock <= 10 ? 'text-warning' : 'text-success') ?>">
                                        (<?= $sStock <= 0 ? 'Hết' : 'Còn ' . $sStock ?>)
                                    </span>
                                <?php endif; ?>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Temperature (Nóng / Lạnh) -->
                <div class="mb-4">
                    <span class="option-label">NHIỆT ĐỘ</span>
                    <div class="vaa-radio-group">
                        <div class="vaa-radio-btn">
                            <input type="radio" name="temperature" id="temp-cold" value="cold" checked>
                            <label for="temp-cold"><i class="fa-solid fa-snowflake me-1 text-primary"></i>Dùng lạnh</label>
                        </div>
                        <div class="vaa-radio-btn">
                            <input type="radio" name="temperature" id="temp-hot" value="hot">
                            <label for="temp-hot"><i class="fa-solid fa-mug-hot me-1 text-danger"></i>Dùng nóng</label>
                        </div>
                    </div>
                </div>

                <!-- Sugar -->
                <div class="mb-4">
                    <span class="option-label">MỨC ĐƯỜNG</span>
                    <div class="vaa-radio-group">
                        <?php foreach ($sugarOptions as $s): ?>
                        <div class="vaa-radio-btn">
                            <input type="radio" name="sugar" id="sugar-<?= $s['value'] ?>" value="<?= $s['value'] ?>" <?= !empty($s['is_default']) ? 'checked' : '' ?>>
                            <label for="sugar-<?= $s['value'] ?>"><?= $s['value'] ?>%</label>
                        </div>
                        <?php endforeach; ?>
                        <?php if (empty($sugarOptions)): ?>
                            <p class="text-muted small mb-0">Không có tùy chọn</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Ice -->
                <div class="mb-4" id="ice-section">
                    <span class="option-label">MỨC ĐÁ</span>
                    <div class="vaa-radio-group">
                        <?php foreach ($iceOptions as $i): ?>
                        <div class="vaa-radio-btn">
                            <input type="radio" name="ice" id="ice-<?= $i['value'] ?>" value="<?= $i['value'] ?>" <?= !empty($i['is_default']) ? 'checked' : '' ?>>
                            <label for="ice-<?= $i['value'] ?>"><?= $i['value'] ?>%</label>
                        </div>
                        <?php endforeach; ?>
                        <?php if (empty($iceOptions)): ?>
                            <p class="text-muted small mb-0">Không có tùy chọn</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Toppings -->
                <?php if (!empty($toppings)): ?>
                <div class="mb-4">
                    <span class="option-label">THÊM TOPPINGS</span>
                    <div class="row g-2">
                        <?php foreach ($toppings as $topping): ?>
                        <div class="col-md-6">
                            <label class="topping-item">
                                <div class="form-check m-0 d-flex align-items-center gap-2">
                                    <input class="form-check-input mt-0" type="checkbox" name="toppings[]" value="<?= (int)$topping['id'] ?>" data-price="<?= (float)$topping['price'] ?>">
                                    <span class="font-sans fw-medium text-dark"><?= e($topping['name']) ?></span>
                                </div>
                                <span class="topping-price">+<?= number_format($topping['price'], 0, ',', '.') ?>đ</span>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Quantity & Action Desktop -->
                <div class="d-none d-lg-block">
                    <div class="row align-items-end g-4">
                        <div class="col-auto">
                            <span class="option-label mb-2">SỐ LƯỢNG</span>
                            <div class="qty-selector">
                                <button type="button" class="qty-btn" id="btn-minus"><i class="fa-solid fa-minus"></i></button>
                                <input type="text" class="qty-input" id="input-qty" value="1" readonly>
                                <button type="button" class="qty-btn" id="btn-plus"><i class="fa-solid fa-plus"></i></button>
                            </div>
                        </div>
                        
                        <div class="col text-end">
                            <span class="d-block small text-muted mb-1">ĐƠN GIÁ: <span id="unit-price" class="fw-bold text-dark"><?= number_format($product['price'], 0, ',', '.') ?>đ</span></span>
                            <span class="d-block option-label mb-0">TỔNG CỘNG</span>
                            <h3 id="total-price" class="text-caramel fw-bold mb-0"><?= number_format($product['price'], 0, ',', '.') ?>đ</h3>
                        </div>
                    </div>
                    
                    <div class="row g-3 mt-3">
                        <div class="col-6">
                            <button type="button" class="btn btn-outline-forest w-100 py-3 fw-bold tracking-wide" id="btn-add-cart">THÊM VÀO GIỎ</button>
                        </div>
                        <div class="col-6">
                            <button type="button" class="btn btn-buy-now w-100 py-3 fw-bold tracking-wide" id="btn-buy-now">MUA NGAY</button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</main>

<!-- Mobile Sticky Action Panel -->
<div class="d-lg-none sticky-bottom-panel">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="font-sans fw-bold text-dark product-detail-style-e7ec96">Tổng: <span id="mobile-total-price" class="text-caramel"><?= number_format($product['price'], 0, ',', '.') ?>đ</span></span>
    </div>
    <div class="row g-2">
        <div class="col-6">
            <button type="button" class="btn btn-outline-forest w-100 py-2 fw-bold" onclick="document.getElementById('btn-add-cart').click()">THÊM VÀO GIỎ</button>
        </div>
        <div class="col-6">
            <button type="button" class="btn btn-buy-now w-100 py-2 fw-bold" onclick="document.getElementById('btn-buy-now').click()">MUA NGAY</button>
        </div>
    </div>
</div>

<!-- Toast for Add to Cart -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="cart-toast" class="toast align-items-center text-bg-success border-0 bg-sage text-forest" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="3000">
        <div class="d-flex">
            <div class="toast-body fw-medium">
                <i class="fa-solid fa-check-circle me-2"></i> Đã thêm sản phẩm vào giỏ hàng.
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<!-- Thông tin sản phẩm & nguyên liệu theo công thức CSDL -->
<section class="container py-5 fade-up visible">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="bg-white p-4 p-md-5 border border-sage border-opacity-25">
                <h2 class="font-serif fw-bold text-forest mb-4">Thông tin sản phẩm</h2>
                <dl class="row mb-0">
                    <dt class="col-sm-4">Danh mục</dt>
                    <dd class="col-sm-8"><?= e($product['category_name'] ?? 'Chưa phân loại') ?></dd>

                    <dt class="col-sm-4">Mô tả</dt>
                    <dd class="col-sm-8"><?= nl2br(e($product['description'] ?? 'Chưa có mô tả.')) ?></dd>

                    <dt class="col-sm-4">Kích cỡ</dt>
                    <dd class="col-sm-8"><?= e(implode(', ', array_column($sizes, 'name'))) ?></dd>

                    <dt class="col-sm-4">Topping áp dụng</dt>
                    <dd class="col-sm-8">
                        <?= !empty($toppings)
                            ? e(implode(', ', array_column($toppings, 'name')))
                            : 'Món này không áp dụng topping.' ?>
                    </dd>

                    <dt class="col-sm-4">Nguyên liệu theo công thức</dt>
                    <dd class="col-sm-8">
                        <?= !empty($ingredientNames)
                            ? e(implode(', ', $ingredientNames))
                            : 'Chưa cấu hình công thức nguyên liệu.' ?>
                    </dd>
                </dl>
            </div>
        </div>
    </div>
</section>

<!-- Đánh giá của khách hàng -->
<section class="container py-5 fade-up visible" id="reviews-section">
    <div class="text-center mb-5">
        <h2 class="font-serif fw-bold text-forest">Đánh giá của khách hàng</h2>
        
        <div class="mt-4">
            <h1 class="font-serif text-caramel mb-0 product-detail-style-394c9a"><?= number_format($product['rating'], 1) ?></h1>
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
            <?php if ($flashSuccess = get_flash('success')): ?>
                <div class="alert alert-success rounded-0 border-0 mb-4"><?= e($flashSuccess) ?></div>
            <?php endif; ?>
            <?php if ($flashError = get_flash('error')): ?>
                <div class="alert alert-danger rounded-0 border-0 mb-4"><?= e($flashError) ?></div>
            <?php endif; ?>

            <!-- Form gửi đánh giá -->
            <div class="review-card mb-5 bg-ivory border border-sage border-opacity-25" id="review-form">
                <h5 class="font-serif fw-bold text-forest mb-3">Viết đánh giá của bạn</h5>
                <?php if (isset($_SESSION['customer_id'])): ?>
                    <form action="<?= e(url('product-detail', ['id' => $product['id'], 'action' => 'review'])) ?>" method="POST">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label small text-muted text-uppercase fw-semibold mb-1">Mức độ hài lòng:</label>
                            <div class="star-rating d-flex align-items-center gap-2 fs-4" style="cursor: pointer;">
                                <input type="hidden" name="rating" id="reviewRatingInput" value="5">
                                <div class="stars-picker text-caramel d-flex gap-1">
                                    <i class="fa-solid fa-star star-item" data-value="1"></i>
                                    <i class="fa-solid fa-star star-item" data-value="2"></i>
                                    <i class="fa-solid fa-star star-item" data-value="3"></i>
                                    <i class="fa-solid fa-star star-item" data-value="4"></i>
                                    <i class="fa-solid fa-star star-item" data-value="5"></i>
                                </div>
                                <span class="fs-6 text-muted ms-2" id="ratingText">5 sao - Rất hài lòng</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small text-muted text-uppercase fw-semibold mb-1">Nhận xét chi tiết:</label>
                            <textarea name="comment" class="form-control rounded-0 border-sage border-opacity-50" rows="3" placeholder="Chia sẻ cảm nhận của bạn về hương vị trà, độ ngọt, trân châu..." required></textarea>
                        </div>

                        <button type="submit" class="btn btn-caramel px-4 py-2 rounded-0">GỬI ĐÁNH GIÁ</button>
                    </form>
                <?php else: ?>
                    <p class="text-muted mb-3">Bạn cần đăng nhập để gửi đánh giá cho sản phẩm này.</p>
                    <a href="<?= e(url('login')) ?>" class="btn btn-outline-forest btn-sm rounded-0 px-4 py-2">ĐĂNG NHẬP ĐỂ ĐÁNH GIÁ</a>
                <?php endif; ?>
            </div>

            <!-- Danh sách đánh giá -->
            <?php if(!empty($reviews)): ?>
                <div class="d-flex flex-column gap-4">
                    <?php foreach($reviews as $review): ?>
                    <div class="review-card">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <div class="review-name"><?= e($review['name']) ?></div>
                                <div class="review-date"><?= e($review['date']) ?></div>
                            </div>
                            <div class="stars small text-caramel">
                                <?php for($i=0; $i<$review['rating']; $i++) echo '<i class="fa-solid fa-star"></i>'; ?>
                            </div>
                        </div>
                        <p class="mb-0 text-dark">"<?= e($review['comment']) ?>"</p>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <?php if (empty($reviews)): ?>
                <p class="text-muted text-center mt-2">Sản phẩm chưa có đánh giá nào. Hãy là người đầu tiên đánh giá!</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const starItems = document.querySelectorAll('.stars-picker .star-item');
    const ratingInput = document.getElementById('reviewRatingInput');
    const ratingText = document.getElementById('ratingText');
    const labels = {
        1: '1 sao - Rất tệ',
        2: '2 sao - Chưa hài lòng',
        3: '3 sao - Bình thường',
        4: '4 sao - Hài lòng',
        5: '5 sao - Rất hài lòng'
    };

    if (starItems.length > 0 && ratingInput) {
        starItems.forEach(star => {
            star.addEventListener('click', function() {
                const val = parseInt(this.getAttribute('data-value'));
                ratingInput.value = val;
                if (ratingText) {
                    ratingText.textContent = labels[val] || (val + ' sao');
                }
                starItems.forEach((s, idx) => {
                    if (idx < val) {
                        s.classList.remove('fa-regular');
                        s.classList.add('fa-solid');
                    } else {
                        s.classList.remove('fa-solid');
                        s.classList.add('fa-regular');
                    }
                });
            });
        });
    }
});
</script>

<!-- Sản phẩm liên quan -->
<?php if (!empty($relatedProducts)): ?>
<section class="container py-5 border-top border-sage border-opacity-25 fade-up visible">
    <div class="text-center mb-5">
        <h2 class="font-serif fw-bold text-forest">Sản phẩm liên quan</h2>
    </div>
    
    <div class="row g-4">
        <?php foreach($relatedProducts as $item): ?>
        <div class="col-6 col-md-3">
            <div class="card product-card h-100 text-center p-2 p-md-3">
                <a href="<?= e(url('product-detail', array('id' => (int)$item['id']))) ?>" class="text-decoration-none">
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

<!-- Script điều khiển chi tiết sản phẩm chuẩn gốc -->
<script src="<?= e(asset('assets/js/product-detail.js')) ?>"></script>
<script>
    // Đồng bộ tổng tiền trên mobile với desktop
    const desktopTotal = document.getElementById('total-price');
    const observer = new MutationObserver(() => {
        const mobileTotal = document.getElementById('mobile-total-price');
        if (mobileTotal && desktopTotal) {
            mobileTotal.textContent = desktopTotal.textContent;
        }
    });
    if (desktopTotal) {
        observer.observe(desktopTotal, { childList: true, characterData: true, subtree: true });
    }
</script>
