<!-- PRODUCT HERO SECTION -->
<section class="product-hero fade-up">
    <div class="container px-4 px-lg-5 text-center">
        <span class="small-label mb-3 d-inline-block">OUR MENU</span>
        <h1 class="mb-4">Khám phá<br>hương vị của bạn</h1>
        <p class="text-muted mx-auto products-style-d99434" >
            Từ những ly trà sữa truyền thống đến những thức uống mang hương vị hiện đại, hãy tìm cho mình một vị yêu thích.
        </p>
        <div class="mt-4">
            <i class="fa-solid fa-leaf text-sage fs-3 products-style-b799bd" ></i>
        </div>
    </div>
</section>

<!-- PRODUCT CONTENT SECTION -->
<section class="pb-5 fade-up products-style-5c9b3d" >
    <div class="container px-4 px-lg-5">
        
                <form id="filter-form" action="index.php" method="GET">
            <input type="hidden" name="route" value="products">
            <input type="hidden" name="category_id" id="category_input" value="<?= htmlspecialchars($currentCategory) ?>">
            
            <!-- CATEGORY NAVIGATION -->
            <div class="category-nav-wrapper text-center text-md-start mb-4" id="category-filter">
                <a href="#" class="category-link <?= $currentCategory === 'all' ? 'active' : '' ?>" data-filter="all">Tất cả</a>
                <?php if (!empty($categoriesList)): ?>
                    <?php foreach ($categoriesList as $category): ?>
                        <a href="#" class="category-link <?= $currentCategory == $category['id'] ? 'active' : '' ?>" data-filter="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- SEARCH & FILTER -->
            <div class="row align-items-center mb-5">
                <div class="col-md-6 mb-3 mb-md-0">
                    <div class="search-wrapper products-style-7b2ab1" >
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" name="search" class="search-input" placeholder="Tìm kiếm sản phẩm..." value="<?= htmlspecialchars($currentSearch) ?>" onkeydown="if(event.key === 'Enter'){this.form.submit();}">
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    <span class="text-muted small me-2">Sắp xếp theo:</span>
                    <select name="sort" class="sort-select" onchange="this.form.submit()">
                        <option value="default" <?= $currentSort === 'default' ? 'selected' : '' ?>>Mặc định</option>
                        <option value="price_asc" <?= $currentSort === 'price_asc' ? 'selected' : '' ?>>Giá thấp → cao</option>
                        <option value="price_desc" <?= $currentSort === 'price_desc' ? 'selected' : '' ?>>Giá cao → thấp</option>
                        <option value="name_asc" <?= $currentSort === 'name_asc' ? 'selected' : '' ?>>Tên A → Z</option>
                    </select>
                </div>
            </div>
        </form>

        <!-- PRODUCT GRID -->
        <div class="row g-4">
            <?php if (!empty($productsList)): ?>
                <?php foreach ($productsList as $product): ?>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 product-item" data-category-id="<?= $product['category_id'] ?>">
                    <div class="product-card h-100 d-flex flex-column border-0 shadow-sm">
                        <a href="index.php?route=product_detail&id=<?= $product['id'] ?>" class="text-decoration-none text-dark d-flex flex-column h-100">
                            <div class="product-image-box position-relative" style="<?= empty($product['image']) ? 'background-color: var(--vaa-sage);' : '' ?>">
                                <div class="fav-icon" onclick="toggleFavoriteList(event, <?= $product['id'] ?>, this)">
                                    <i class="<?= in_array($product['id'], $favorites ?? []) ? 'fa-solid text-danger' : 'fa-regular' ?> fa-heart"></i>
                                </div>
                                <?php if (!empty($product['image'])): ?>
                                    <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="img-fluid drop-shadow">
                                <?php endif; ?>
                            </div>
                            <div class="p-4 bg-white d-flex flex-column flex-grow-1">
                                <h6 class="font-serif fw-bold fs-5 mb-1 text-center"><?= htmlspecialchars($product['name']) ?></h6>
                                <p class="text-muted small text-center mb-3 line-clamp-2"><?= htmlspecialchars($product['description'] ?? '') ?></p>
                                <p class="text-caramel fw-bold mb-3 mt-auto fs-5 text-center"><?= number_format($product['price'], 0, ',', '.') ?>đ</p>
                                <button type="button" class="btn btn-outline-forest w-100 rounded-pill py-2 fw-semibold products-style-e7992d" >
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
        <?php if (isset($totalPages) && $totalPages > 1): ?>
        <?php 
            $queryString = '';
            if (!empty($currentSearch)) $queryString .= '&search=' . urlencode($currentSearch);
            if (!empty($currentCategory) && $currentCategory !== 'all') $queryString .= '&category_id=' . urlencode($currentCategory);
            if (!empty($currentSort) && $currentSort !== 'default') $queryString .= '&sort=' . urlencode($currentSort);
        ?>
        <ul class="vaa-pagination">
            <li>
                <a href="<?= $currentPage > 1 ? 'index.php?route=products' . $queryString . '&page=' . ($currentPage - 1) : '#' ?>">
                    <i class="fa-solid fa-chevron-left <?= $currentPage <= 1 ? 'text-muted' : '' ?>"></i>
                </a>
            </li>
            
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li>
                    <a href="index.php?route=products<?= $queryString ?>&page=<?= $i ?>" class="<?= $i === $currentPage ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>
            
            <li>
                <a href="<?= $currentPage < $totalPages ? 'index.php?route=products' . $queryString . '&page=' . ($currentPage + 1) : '#' ?>">
                    <i class="fa-solid fa-chevron-right <?= $currentPage >= $totalPages ? 'text-muted' : '' ?>"></i>
                </a>
            </li>
        </ul>
        <?php endif; ?>

    </div>
</section>

<script>
    function toggleFavoriteList(event, productId, el) {
        event.preventDefault(); // Ngan click vao the anchor <a>
        event.stopPropagation();
        
        fetch('index.php?route=favorites&action=toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'product_id=' + productId
        })
        .then(response => response.json())
        .then(data => {
            if (data.require_login) {
                alert(data.message);
                window.location.href = 'index.php?route=login';
                return;
            }
            if (data.success) {
                const icon = el.querySelector('i');
                if (data.is_favorited) {
                    icon.classList.remove('fa-regular');
                    icon.classList.add('fa-solid', 'text-danger');
                } else {
                    icon.classList.remove('fa-solid', 'text-danger');
                    icon.classList.add('fa-regular');
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const categoryLinks = document.querySelectorAll('#category-filter .category-link');
        const categoryInput = document.getElementById('category_input');
        const filterForm = document.getElementById('filter-form');

        categoryLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                categoryInput.value = this.getAttribute('data-filter');
                filterForm.submit();
            });
        });
    });
</script>
