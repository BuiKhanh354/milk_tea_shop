<!-- pages/products/index.php — Giao diện danh sách sản phẩm VAA THÉ -->
<section class="products-section py-5">
    <div class="container">
        
        <!-- Header Danh mục & Tìm kiếm -->
        <div class="row align-items-center mb-5">
            <div class="col-md-6">
                <span class="text-uppercase tracking-wider text-muted small fw-semibold">Menu Thức Uống</span>
                <h1 class="font-serif display-5 fw-bold text-forest mt-1">Hương Vị Tinh Tuyển</h1>
            </div>
            <div class="col-md-6 mt-4 mt-md-0">
                <form action="<?= e(url('products')) ?>" method="GET" class="d-flex justify-content-md-end">
                    <input type="hidden" name="page" value="products">
                    <?php if ($categoryId): ?>
                        <input type="hidden" name="category" value="<?= e($categoryId) ?>">
                    <?php endif; ?>
                    <div class="input-group search-box" style="max-width: 320px;">
                        <input type="text" name="search" class="form-control rounded-pill-start border-end-0 ps-3" placeholder="Tìm thức uống..." value="<?= e($search) ?>">
                        <button class="btn btn-outline-forest rounded-pill-end border-start-0 pe-3" type="submit">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Bộ lọc danh mục (Tabs) -->
        <div class="category-tabs-wrapper mb-5">
            <div class="category-tabs d-flex gap-2 overflow-auto pb-2">
                <a href="<?= e(url('products', $search ? ['search' => $search] : [])) ?>" 
                   class="btn btn-tab <?= empty($categoryId) ? 'active' : '' ?>">
                    Tất cả
                </a>
                <?php foreach ($categories as $cat): ?>
                    <a href="<?= e(url('products', array_merge(['category' => $cat['id']], $search ? ['search' => $search] : []))) ?>" 
                       class="btn btn-tab <?= ($categoryId == $cat['id']) ? 'active' : '' ?>">
                        <?= e($cat['name']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Danh sách sản phẩm (Grid) -->
        <div class="row g-4">
            <?php if (!empty($productsList)): ?>
                <?php foreach ($productsList as $product): ?>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 product-item">
                    <div class="product-card h-100 d-flex flex-column border-0 shadow-sm">
                        <div class="product-image-box position-relative">
                            <a href="<?= e(url('product-detail', ['id' => $product['id']])) ?>" class="product-image-link" aria-label="Xem chi tiết <?= e($product['name']) ?>">
                                <img src="<?= e(product_image($product['image'])) ?>" alt="<?= e($product['name']) ?>" class="img-fluid drop-shadow">
                            </a>
                            <button type="button" class="fav-icon border-0" aria-label="Thêm hoặc bỏ yêu thích" onclick="toggleFavoriteList(event, <?= (int)$product['id'] ?>, this)">
                                <i class="<?= in_array($product['id'], $favorites ?? []) ? 'fa-solid text-danger' : 'fa-regular' ?> fa-heart"></i>
                            </button>
                        </div>
                        <a href="<?= e(url('product-detail', ['id' => $product['id']])) ?>" class="text-decoration-none text-dark p-4 bg-white d-flex flex-column flex-grow-1">
                            <h6 class="font-serif fw-bold fs-5 mb-1 text-center"><?= e($product['name']) ?></h6>
                            <p class="text-muted small text-center mb-3 line-clamp-2"><?= e($product['description'] ?? '') ?></p>
                            <p class="text-caramel fw-bold mb-3 mt-auto fs-5 text-center"><?= money($product['price']) ?></p>
                            <span class="btn btn-outline-forest w-100 rounded-pill py-2 fw-semibold">
                                <i class="fa-solid fa-arrow-right me-2"></i> Xem chi tiết
                            </span>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <div class="empty-state py-5">
                        <i class="fa-solid fa-mug-hot fa-3x text-muted mb-3 opacity-50"></i>
                        <h4 class="font-serif text-muted">Không tìm thấy sản phẩm nào</h4>
                        <p class="text-muted small mb-4">Vui lòng thử tìm kiếm khác hoặc chuyển sang danh mục khác.</p>
                        <a href="<?= e(url('products')) ?>" class="btn btn-forest px-4 rounded-pill">Xem tất cả sản phẩm</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Phân trang -->
        <?php if ($totalPages > 1): ?>
        <div class="d-flex justify-content-center mt-5">
            <nav aria-label="Page navigation">
                <ul class="pagination pagination-custom gap-2">
                    <?php if ($currentPageNumber > 1): ?>
                        <li class="page-item">
                            <a class="page-link rounded-circle" href="<?= e(url('products', array_merge(['p' => $currentPageNumber - 1], $categoryId ? ['category' => $categoryId] : [], $search ? ['search' => $search] : []))) ?>" aria-label="Previous">
                                <i class="fa-solid fa-chevron-left"></i>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= ($i === $currentPageNumber) ? 'active' : '' ?>">
                            <a class="page-link rounded-circle" href="<?= e(url('products', array_merge(['p' => $i], $categoryId ? ['category' => $categoryId] : [], $search ? ['search' => $search] : []))) ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($currentPageNumber < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link rounded-circle" href="<?= e(url('products', array_merge(['p' => $currentPageNumber + 1], $categoryId ? ['category' => $categoryId] : [], $search ? ['search' => $search] : []))) ?>" aria-label="Next">
                                <i class="fa-solid fa-chevron-right"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
        <?php endif; ?>

    </div>
</section>

<script>
function toggleFavoriteList(event, productId, btnElement) {
    event.preventDefault();
    event.stopPropagation();
    
    fetch('<?= e(url('yeuthich', ['action' => 'toggle'])) ?>', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: new URLSearchParams({product_id: productId})
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'unauthorized') {
            window.location.href = '<?= e(url('login')) ?>';
            return;
        }
        if (data.status === 'success') {
            const icon = btnElement.querySelector('i');
            if (data.action === 'added') {
                icon.classList.remove('fa-regular');
                icon.classList.add('fa-solid', 'text-danger');
            } else {
                icon.classList.remove('fa-solid', 'text-danger');
                icon.classList.add('fa-regular');
            }
        }
    }).catch(() => alert('Không thể cập nhật danh sách yêu thích. Vui lòng thử lại.'));
}
</script>

