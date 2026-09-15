<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <div class="container mt-4 mb-3 fade-up visible">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb vaa-breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="index.php">HOME</a></li>
                <li class="breadcrumb-item"><a href="products.php">MENU</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= mb_strtoupper($product['name']) ?></li>
            </ol>
        </nav>
    </div>

    <!-- Product Detail Section -->
    <main class="container mb-5 fade-up visible">
        <div class="row g-5">
            <!-- Left: Product Image -->
            <div class="col-lg-6">
                <div class="product-image-container sticky-top" style="top: 100px;">
                    <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="img-fluid">
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
                
                <div class="d-flex align-items-center gap-4 mb-4 pb-4 border-bottom border-sage border-opacity-25">
                    <div id="base-price" data-price="<?= $product['price'] ?>" class="product-price">
                        <?= number_format($product['price'], 0, ',', '.') ?>đ
                    </div>
                    <button class="btn btn-favorite" id="btn-favorite" aria-label="Thêm vào yêu thích">
                        <i class="fa-regular fa-heart fs-4"></i>
                    </button>
                </div>

                <!-- Form Options -->
                <form id="product-options-form">
                    
                    <!-- Size -->
                    <div class="mb-4">
                        <span class="option-label">CHOOSE YOUR SIZE</span>
                        <div class="vaa-radio-group">
                            <?php foreach ($sizes as $index => $size): ?>
                            <div class="vaa-radio-btn">
                                <input type="radio" name="size" id="size-<?= strtolower($size['name']) ?>" value="<?= htmlspecialchars($size['name']) ?>" data-price="<?= $size['extra_price'] ?>" <?= $index === 0 ? 'checked' : '' ?>>
                                <label for="size-<?= strtolower($size['name']) ?>"><?= htmlspecialchars($size['name']) ?> (+<?= number_format($size['extra_price'], 0, ',', '.') ?>đ)</label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Sugar -->
                    <div class="mb-4">
                        <span class="option-label">SUGAR LEVEL</span>
                        <div class="vaa-radio-group">
                            <div class="vaa-radio-btn"><input type="radio" name="sugar" id="sugar-0" value="0"><label for="sugar-0">0%</label></div>
                            <div class="vaa-radio-btn"><input type="radio" name="sugar" id="sugar-25" value="25"><label for="sugar-25">25%</label></div>
                            <div class="vaa-radio-btn"><input type="radio" name="sugar" id="sugar-50" value="50" checked><label for="sugar-50">50%</label></div>
                            <div class="vaa-radio-btn"><input type="radio" name="sugar" id="sugar-75" value="75"><label for="sugar-75">75%</label></div>
                            <div class="vaa-radio-btn"><input type="radio" name="sugar" id="sugar-100" value="100"><label for="sugar-100">100%</label></div>
                        </div>
                    </div>

                    <!-- Ice -->
                    <div class="mb-4">
                        <span class="option-label">ICE LEVEL</span>
                        <div class="vaa-radio-group">
                            <div class="vaa-radio-btn"><input type="radio" name="ice" id="ice-0" value="0"><label for="ice-0">0%</label></div>
                            <div class="vaa-radio-btn"><input type="radio" name="ice" id="ice-25" value="25"><label for="ice-25">25%</label></div>
                            <div class="vaa-radio-btn"><input type="radio" name="ice" id="ice-50" value="50" checked><label for="ice-50">50%</label></div>
                            <div class="vaa-radio-btn"><input type="radio" name="ice" id="ice-75" value="75"><label for="ice-75">75%</label></div>
                            <div class="vaa-radio-btn"><input type="radio" name="ice" id="ice-100" value="100"><label for="ice-100">100%</label></div>
                        </div>
                    </div>

                    <!-- Toppings -->
                    <div class="mb-4">
                        <span class="option-label">ADD TOPPINGS</span>
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
                    
                    <!-- Note -->
                    <div class="mb-4 pb-4 border-bottom border-sage border-opacity-25">
                        <span class="option-label">NOTE FOR YOUR DRINK</span>
                        <textarea class="form-control bg-transparent border-sage rounded-0" rows="2" placeholder="Ví dụ: Ít ngọt hơn, nhiều trân châu..."></textarea>
                    </div>

                    <!-- Quantity & Action Desktop -->
                    <div class="d-none d-lg-block">
                        <div class="row align-items-end g-4">
                            <div class="col-auto">
                                <span class="option-label mb-2">QUANTITY</span>
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
                                <button type="button" class="btn btn-buy-now w-100 py-3 fw-bold tracking-wide">MUA NGAY</button>
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
            <span class="font-sans fw-bold text-dark" style="font-size: 1.1rem;">Tổng: <span id="mobile-total-price" class="text-caramel">35.000đ</span></span>
        </div>
        <div class="row g-2">
            <div class="col-6">
                <button type="button" class="btn btn-outline-forest w-100 py-2 fw-bold" onclick="document.getElementById('btn-add-cart').click()">THÊM VÀO GIỎ</button>
            </div>
            <div class="col-6">
                <button type="button" class="btn btn-buy-now w-100 py-2 fw-bold">MUA NGAY</button>
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

    <!-- Product Story Section -->
    <section class="story-section fade-up visible">
        <div class="container text-center">
            <span class="small-label d-block mb-3">PRODUCT INFORMATION</span>
            <h2 class="story-heading">The Story Behind The Tea</h2>
            <div class="row justify-content-center mt-5">
                <div class="col-md-8 text-start">
                    <p class="fs-5 text-dark mb-4 text-center" style="font-style: italic; font-family: var(--font-serif);">"<?= htmlspecialchars($product['story']) ?>"</p>
                    
                    <div class="bg-white p-4 p-md-5 border border-sage border-opacity-25 mt-5">
                        <h4 class="font-serif fw-bold text-forest mb-4 border-bottom pb-2">Information</h4>
                        <div class="row g-3">
                            <div class="col-sm-4 fw-bold text-dark">Category</div>
                            <div class="col-sm-8 text-muted"><?= htmlspecialchars($product['category_name']) ?></div>
                            
                            <div class="col-sm-4 fw-bold text-dark">Ingredients</div>
                            <div class="col-sm-8 text-muted"><?= htmlspecialchars($product['ingredients']) ?></div>
                            
                            <div class="col-sm-4 fw-bold text-dark">Available sizes</div>
                            <div class="col-sm-8 text-muted">M, L, XL</div>
                            
                            <div class="col-sm-4 fw-bold text-dark">Allergens</div>
                            <div class="col-sm-8 text-muted">Contains Milk, Traces of Nuts</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Reviews Section -->
    <section class="container py-5 fade-up visible">
        <div class="text-center mb-5">
            <span class="small-label d-block mb-2">TESTIMONIALS</span>
            <h2 class="font-serif fw-bold text-forest">Customer Reviews</h2>
            
            <div class="mt-4">
                <h1 class="font-serif text-caramel mb-0" style="font-size: 3.5rem;"><?= number_format($product['rating'], 1) ?></h1>
                <div class="fs-4 stars my-2">
                    <?php 
                        for($i=1; $i<=5; $i++) {
                            if($i <= $fullStars) echo '<i class="fa-solid fa-star"></i>';
                            elseif($i == $fullStars + 1 && $halfStar) echo '<i class="fa-solid fa-star-half-stroke"></i>';
                            else echo '<i class="fa-regular fa-star"></i>';
                        }
                    ?>
                </div>
                <p class="text-muted">Based on <?= $product['review_count'] ?> reviews</p>
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
                
                <div class="mt-5 text-center p-4 bg-cream border border-sage border-opacity-25 rounded-0">
                    <h5 class="font-serif text-forest fw-bold mb-3">Write a review</h5>
                    <?php if(isset($_SESSION['customer_id'])): ?>
                        <form>
                            <div class="mb-3 text-start">
                                <label class="form-label text-dark fw-bold">Your Rating</label>
                                <div class="fs-4 text-sage" style="cursor: pointer;">
                                    <i class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i>
                                </div>
                            </div>
                            <div class="mb-3 text-start">
                                <label class="form-label text-dark fw-bold">Comment</label>
                                <textarea class="form-control bg-white border-0" rows="3" placeholder="Share your experience..."></textarea>
                            </div>
                            <button type="button" class="btn btn-outline-forest px-5 py-2">SUBMIT REVIEW</button>
                        </form>
                    <?php else: ?>
                        <p class="text-muted mb-4">Please login to write a review.</p>
                        <a href="login.php" class="btn btn-caramel px-5 py-2">LOGIN</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Products -->
    <section class="container py-5 border-top border-sage border-opacity-25 fade-up visible">
        <div class="text-center mb-5">
            <h2 class="font-serif fw-bold text-forest">You May Also Like</h2>
        </div>
        
        <div class="row g-4">
            <?php foreach($relatedProducts as $item): ?>
            <div class="col-6 col-md-3">
                <div class="card product-card h-100 text-center p-2 p-md-3">
                    <a href="product_detail.php?id=<?= $item['id'] ?>" class="text-decoration-none">
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
    <?php include __DIR__ . '/../../layouts/footer.html'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Observer for fade-up animation -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/product-detail.js"></script>
    
    <!-- Sync mobile total with desktop total -->
    <script>
        // Update mobile total whenever desktop total changes
        const observer = new MutationObserver((mutations) => {
            const mobileTotal = document.getElementById('mobile-total-price');
            const desktopTotal = document.getElementById('total-price');
            if (mobileTotal && desktopTotal) {
                mobileTotal.textContent = desktopTotal.textContent;
            }
        });
        observer.observe(document.getElementById('total-price'), { childList: true, characterData: true, subtree: true });
    </script>
</body>
</html>
