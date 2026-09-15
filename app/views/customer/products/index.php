<!-- PRODUCT HERO SECTION -->
<section class="product-hero fade-up">
    <div class="container px-4 px-lg-5 text-center">
        <span class="small-label mb-3 d-inline-block">OUR MENU</span>
        <h1 class="mb-4">Khám phá<br>hương vị của bạn</h1>
        <p class="text-muted mx-auto" style="max-width: 500px;">
            Từ những ly trà sữa truyền thống đến những thức uống mang hương vị hiện đại, hãy tìm cho mình một vị yêu thích.
        </p>
        <div class="mt-4">
            <i class="fa-solid fa-leaf text-sage fs-3" style="opacity: 0.6;"></i>
        </div>
    </div>
</section>

<!-- PRODUCT CONTENT SECTION -->
<section class="pb-5 fade-up" style="background-color: var(--vaa-ivory);">
    <div class="container px-4 px-lg-5">
        
        <!-- CATEGORY NAVIGATION -->
        <div class="category-nav-wrapper text-center text-md-start">
            <a href="#" class="category-link active">Tất cả</a>
            <a href="#" class="category-link">Trà sữa</a>
            <a href="#" class="category-link">Trà trái cây</a>
            <a href="#" class="category-link">Đá xay</a>
            <a href="#" class="category-link">Cà phê</a>
            <a href="#" class="category-link">Nước ép</a>
            <a href="#" class="category-link">Topping</a>
        </div>

        <!-- SEARCH & FILTER -->
        <div class="row align-items-center mb-5">
            <div class="col-md-6 mb-3 mb-md-0">
                <div class="search-wrapper" style="max-width: 350px;">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" class="search-input" placeholder="Tìm kiếm sản phẩm...">
                </div>
            </div>
            <div class="col-md-6 text-md-end">
                <span class="text-muted small me-2">Sắp xếp theo:</span>
                <select class="sort-select">
                    <option value="default">Mặc định</option>
                    <option value="price_asc">Giá thấp → cao</option>
                    <option value="price_desc">Giá cao → thấp</option>
                    <option value="name_asc">Tên A → Z</option>
                </select>
            </div>
        </div>

        <!-- PRODUCT GRID -->
        <div class="row g-4">
            <?php if (!empty($productsList)): ?>
                <?php foreach ($productsList as $product): ?>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="product-card h-100 d-flex flex-column border-0 shadow-sm">
                        <a href="product_detail.php?id=<?= $product['id'] ?>" class="text-decoration-none text-dark d-flex flex-column h-100">
                            <div class="product-image-box position-relative" style="<?= empty($product['image']) ? 'background-color: var(--vaa-sage);' : '' ?>">
                                <div class="fav-icon">
                                    <i class="fa-regular fa-heart"></i>
                                </div>
                                <?php if (!empty($product['image'])): ?>
                                    <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="img-fluid drop-shadow">
                                <?php endif; ?>
                            </div>
                            <div class="p-4 bg-white d-flex flex-column flex-grow-1">
                                <h6 class="font-serif fw-bold fs-5 mb-1 text-center"><?= htmlspecialchars($product['name']) ?></h6>
                                <p class="text-muted small text-center mb-3 line-clamp-2"><?= htmlspecialchars($product['description'] ?? '') ?></p>
                                <p class="text-caramel fw-bold mb-3 mt-auto fs-5 text-center"><?= number_format($product['price'], 0, ',', '.') ?>đ</p>
                                <button type="button" class="btn btn-outline-forest w-100 rounded-pill py-2 fw-semibold" style="font-size: 0.85rem;">
                                    <i class="fa-solid fa-plus me-2"></i> Chi tiết
                                </button>
                            </div>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <p class="text-muted fs-5">Hiện tại chưa có sản phẩm nào.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- PAGINATION -->
        <ul class="vaa-pagination">
            <li><a href="#"><i class="fa-solid fa-chevron-left text-muted"></i></a></li>
            <li><a href="#" class="active">1</a></li>
            <li><a href="#">2</a></li>
            <li><a href="#">3</a></li>
            <li><a href="#"><i class="fa-solid fa-chevron-right text-muted"></i></a></li>
        </ul>

    </div>
</section>
