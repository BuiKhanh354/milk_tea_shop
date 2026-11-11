<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= csrf_meta() ?>
    <title><?= htmlspecialchars($product['name']) ?> - VAA THÉ</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/product-detail.css">
</head>
<body class="bg-ivory">

    <!-- Header -->
    <?php include __DIR__ . '/../../partials/navbar_old.php'; ?>
    
    <!-- Breadcrumb -->
    <div class="container page-offset mb-3 fade-up visible">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb vaa-breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="index.php">HOME</a></li>
                <li class="breadcrumb-item"><a href="index.php?route=products">MENU</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= mb_strtoupper($product['name']) ?></li>
            </ol>
        </nav>
    </div>

    <!-- Product Detail Section -->
    <main class="container mb-5 fade-up visible">
        <div class="row g-5">
            <!-- Left: Product Image -->
            <div class="col-lg-6">
                <div class="product-image-container sticky-top product-detail-style-c9a35f" >
                    <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="img-fluid" id="product-img-main">
                </div>
            </div>
            
            <!-- Right: Product Info & Options -->
            <div class="col-lg-6">
                <span class="product-category"><?= htmlspecialchars($product['category_name']) ?></span>
                <h1 class="product-title"><?= htmlspecialchars($product['name']) ?></h1>
                
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
                
                <p class="product-description"><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                
                <div class="d-flex align-items-center justify-content-between gap-3 mb-4 pb-4 border-bottom border-sage border-opacity-25 flex-wrap">
                    <div class="d-flex align-items-center gap-3">
                        <div id="base-price" data-price="<?= $product['price'] ?>" class="product-price">
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
                    <button type="button" class="btn btn-favorite <?= isset($isFavorited) && $isFavorited ? 'active' : '' ?>" id="btn-favorite" aria-label="Thêm hoặc bỏ yêu thích <?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?>" data-product-id="<?= (int)$product['id'] ?>">
                        <i class="<?= isset($isFavorited) && $isFavorited ? 'fa-solid text-danger' : 'fa-regular' ?> fa-heart fs-4"></i>
                    </button>
                </div>

                <!-- Form Options -->
                <form id="product-options-form">
                    
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
                                       value="<?= htmlspecialchars($size['name']) ?>" 
                                       data-price="<?= $size['extra_price'] ?>"
                                       data-size-id="<?= $size['id'] ?>"
                                       data-stock="<?= $sStock !== null ? $sStock : '' ?>"
                                       <?= $index === 0 ? 'checked' : '' ?>>
                                <label for="size-<?= strtolower($size['name']) ?>">
                                    <?= htmlspecialchars($size['name']) ?> (+<?= number_format($size['extra_price'], 0, ',', '.') ?>đ)
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
                                <label for="temp-cold"><i class="fa-solid me-1 text-primary"></i>Dùng lạnh</label>
                            </div>
                            <div class="vaa-radio-btn">
                                <input type="radio" name="temperature" id="temp-hot" value="hot">
                                <label for="temp-hot"><i class="fa-solid me-1 text-danger"></i>Dùng nóng</label>
                            </div>
                        </div>
                    </div>

                    <!-- Sugar -->
                    <div class="mb-4">
                        <span class="option-label">MỨC ĐƯỜNG</span>
                        <div class="vaa-radio-group">
                            <?php foreach ($sugarOptions as $s): ?>
                            <div class="vaa-radio-btn">
                                <input type="radio" name="sugar" id="sugar-<?= $s['value'] ?>" value="<?= $s['value'] ?>" <?= $s['is_default'] ? 'checked' : '' ?>>
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
                                <input type="radio" name="ice" id="ice-<?= $i['value'] ?>" value="<?= $i['value'] ?>" <?= $i['is_default'] ? 'checked' : '' ?>>
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
                                        <input class="form-check-input mt-0" type="checkbox" name="toppings[]" value="<?= htmlspecialchars($topping['id']) ?>" data-price="<?= $topping['price'] ?>">
                                        <span class="font-sans fw-medium text-dark"><?= htmlspecialchars($topping['name']) ?></span>
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
                                <span class="d-block small text-muted mb-1">ĐƠN GIÁ: <span id="unit-price" class="fw-bold text-dark">35.000đ</span></span>
                                <span class="d-block option-label mb-0">TỔNG CỘNG</span>
                                <h3 id="total-price" class="text-caramel fw-bold mb-0">35.000đ</h3>
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
            <span class="font-sans fw-bold text-dark product-detail-style-e7ec96" >Tổng: <span id="mobile-total-price" class="text-caramel">35.000đ</span></span>
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

    <!-- Thông tin lấy từ dữ liệu sản phẩm và công thức trong CSDL -->
    <section class="container py-5 fade-up visible">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="bg-white p-4 p-md-5 border border-sage border-opacity-25">
                    <h2 class="font-serif fw-bold text-forest mb-4">Thông tin sản phẩm</h2>
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Danh mục</dt>
                        <dd class="col-sm-8"><?= htmlspecialchars($product['category_name'] ?? 'Chưa phân loại', ENT_QUOTES, 'UTF-8') ?></dd>

                        <dt class="col-sm-4">Mô tả</dt>
                        <dd class="col-sm-8"><?= nl2br(htmlspecialchars($product['description'] ?? 'Chưa có mô tả.', ENT_QUOTES, 'UTF-8')) ?></dd>

                        <dt class="col-sm-4">Kích cỡ</dt>
                        <dd class="col-sm-8"><?= htmlspecialchars(implode(', ', array_column($sizes, 'name')), ENT_QUOTES, 'UTF-8') ?></dd>

                        <dt class="col-sm-4">Nguyên liệu theo công thức</dt>
                        <dd class="col-sm-8">
                            <?= !empty($ingredientNames)
                                ? htmlspecialchars(implode(', ', $ingredientNames), ENT_QUOTES, 'UTF-8')
                                : 'Chưa cấu hình công thức nguyên liệu.' ?>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </section>

    <!-- Reviews Section -->
    <section class="container py-5 fade-up visible">
        <div class="text-center mb-5">
            <h2 class="font-serif fw-bold text-forest">Đánh giá của khách hàng</h2>
            
            <div class="mt-4">
                <h1 class="font-serif text-caramel mb-0 product-detail-style-394c9a" ><?= number_format($product['rating'], 1) ?></h1>
                <div class="fs-4 stars my-2">
                    <?php 
                        for($i=1; $i<=5; $i++) {
                            if($i <= $fullStars) echo '<i class="fa-solid fa-star"></i>';
                            elseif($i == $fullStars + 1 && $halfStar) echo '<i class="fa-solid fa-star-half-stroke"></i>';
                            else echo '<i class="fa-regular fa-star"></i>';
                        }
                    ?>
                </div>
                <p class="text-muted"><?= (int)$product['review_count'] ?> lượt đánh giá</p>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <?php if(!empty($reviews)): ?>
                    <div class="d-flex flex-column gap-4">
                        <?php foreach($reviews as $review): ?>
                        <div class="review-card">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <div class="review-name"><?= htmlspecialchars($review['name']) ?></div>
                                    <div class="review-date"><?= htmlspecialchars($review['date']) ?></div>
                                </div>
                                <div class="stars small">
                                    <?php for($i=0; $i<$review['rating']; $i++) echo '<i class="fa-solid fa-star"></i>'; ?>
                                </div>
                            </div>
                            <p class="mb-0 text-dark">"<?= htmlspecialchars($review['comment']) ?>"</p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <?php if (empty($reviews)): ?>
                    <p class="text-muted text-center mt-4">Sản phẩm chưa có đánh giá.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Related Products -->
    <section class="container py-5 border-top border-sage border-opacity-25 fade-up visible">
        <div class="text-center mb-5">
            <h2 class="font-serif fw-bold text-forest">Sản phẩm liên quan</h2>
        </div>
        
        <div class="row g-4">
            <?php foreach($relatedProducts as $item): ?>
            <div class="col-6 col-md-3">
                <div class="card product-card h-100 text-center p-2 p-md-3">
                    <a href="index.php?route=product_detail&amp;id=<?= (int)$item['id'] ?>" class="text-decoration-none">
                        <img src="<?= htmlspecialchars($item['image']) ?>" class="card-img-top mb-3" alt="<?= htmlspecialchars($item['name']) ?>">
                        <h5 class="product-card-title"><?= htmlspecialchars($item['name']) ?></h5>
                        <p class="text-caramel fw-bold mb-0"><?= number_format($item['price'], 0, ',', '.') ?>đ</p>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Footer -->
    <?php include __DIR__ . '/../../layouts/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Observer for fade-up animation -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/product-detail.js"></script>
    
    <!-- Sync mobile total with desktop total -->
    <script>
        // Update mobile total whenever desktop total changes
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
</body>
</html>


