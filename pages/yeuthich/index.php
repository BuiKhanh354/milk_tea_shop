<!-- pages/yeuthich/index.php — Giao diện Danh Sách Yêu Thích -->
<section class="favorites-section py-5">
    <div class="container">
        
        <h1 class="font-serif display-5 fw-bold text-forest mb-4">Món Ngon Yêu Thích</h1>

        <?php if (!isset($_SESSION['customer_id'])): ?>
            <div class="text-center py-5">
                <i class="fa-regular fa-heart fa-4x text-muted mb-3 opacity-50"></i>
                <h4 class="font-serif text-forest mb-2">Vui lòng đăng nhập</h4>
                <p class="text-muted mb-4">Bạn cần đăng nhập tài khoản để lưu lại những món yêu thích của mình.</p>
                <a href="<?= e(url('login')) ?>" class="btn btn-forest px-4 py-2 rounded-pill fw-semibold">Đăng Nhập Ngay</a>
            </div>
        <?php elseif (!empty($favoritesList)): ?>
            <div class="row g-4">
                <?php foreach ($favoritesList as $product): ?>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 product-item" id="fav-item-<?= $product['id'] ?>">
                    <div class="product-card h-100 d-flex flex-column border-0 shadow-sm">
                        <div class="product-image-box position-relative">
                            <a href="<?= e(url('product-detail', ['id' => $product['id']])) ?>">
                                <img src="<?= e(product_image($product['image'])) ?>" alt="<?= e($product['name']) ?>" class="img-fluid drop-shadow">
                            </a>
                            <button type="button" class="fav-icon border-0" onclick="removeFavorite(<?= (int)$product['id'] ?>)">
                                <i class="fa-solid fa-heart text-danger"></i>
                            </button>
                        </div>
                        <a href="<?= e(url('product-detail', ['id' => $product['id']])) ?>" class="text-decoration-none text-dark p-4 bg-white d-flex flex-column flex-grow-1 text-center">
                            <h6 class="font-serif fw-bold fs-5 mb-1"><?= e($product['name']) ?></h6>
                            <p class="text-muted small mb-3 line-clamp-2"><?= e($product['description'] ?? '') ?></p>
                            <p class="text-caramel fw-bold mb-3 mt-auto fs-5"><?= money($product['price']) ?></p>
                            <span class="btn btn-outline-forest w-100 rounded-pill py-2 fw-semibold">
                                <i class="fa-solid fa-arrow-right me-2"></i> Xem chi tiết
                            </span>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fa-regular fa-heart fa-4x text-muted mb-3 opacity-50"></i>
                <h4 class="font-serif text-forest mb-2">Chưa có món yêu thích nào</h4>
                <p class="text-muted mb-4">Hãy bấm vào biểu tượng trái tim trên các món trà sữa để thêm vào đây nhé!</p>
                <a href="<?= e(url('products')) ?>" class="btn btn-forest px-4 py-2 rounded-pill fw-semibold">Khám Phá Menu</a>
            </div>
        <?php endif; ?>

    </div>
</section>

<script>
function removeFavorite(productId) {
    if (!confirm('Bỏ món này khỏi danh sách yêu thích?')) return;
    fetch('<?= e(url('yeuthich', ['action' => 'toggle'])) ?>', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: new URLSearchParams({product_id: productId})
    })
    .then(r => r.json())
    .then(data => {
        if (data.status === 'success') {
            document.getElementById('fav-item-' + productId)?.remove();
        }
    });
}
</script>

