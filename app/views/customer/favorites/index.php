<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sản phẩm yêu thích - VAA THÉ</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-ivory">

    <!-- Header tối giản -->
    <?php include '../app/views/partials/account-header.php'; ?>
    
    <main class="container py-5 account-style-a6ee44" >
        <div class="row g-4">
            <!-- Sidebar (Cột Trái) -->
            <div class="col-lg-3">
                <?php include '../app/views/partials/account-sidebar.php'; ?>
            </div>
            
            <!-- Content (Cột Phải) -->
            <div class="col-lg-9">
                <div class="bg-white p-4 p-md-5 border border-sage border-opacity-25 shadow-sm h-100 fade-up visible">
                    
                    <div class="mb-5 pb-3 border-bottom border-light">
                        <span class="small-label d-block mb-1">FAVORITES</span>
                        <h3 class="font-serif fw-bold text-forest mb-0">Sản phẩm yêu thích</h3>
                    </div>

                    <?php if(empty($favorites)): ?>
                        <div class="text-center py-5">
                            <i class="fa-regular fa-heart fs-1 text-muted opacity-25 mb-3"></i>
                            <h4 class="font-serif text-forest">Bạn chưa có sản phẩm yêu thích</h4>
                            <p class="text-muted">Hãy thêm những món trà yêu thích vào đây để dễ dàng đặt lại.</p>
                            <a href="index.php?route=products" class="btn btn-caramel mt-3">KHÁM PHÁ MENU</a>
                        </div>
                    <?php else: ?>
                        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
                            <?php foreach($favorites as $product): ?>
                            <div class="col" id="favorite-<?= $product['id'] ?>">
                                <div class="card h-100 border-0 rounded-4 shadow-sm product-card">
                                    <div class="position-relative overflow-hidden rounded-top-4">
                                        <img src="<?= htmlspecialchars($product['image']) ?>" class="card-img-top object-fit-cover" alt="<?= htmlspecialchars($product['name']) ?>" style="height: 200px;">
                                        <button class="btn btn-favorite active position-absolute top-0 end-0 m-3" onclick="toggleFavorite(<?= $product['id'] ?>)" aria-label="Bỏ yêu thích">
                                            <i class="fa-solid fa-heart fs-4 text-danger"></i>
                                        </button>
                                    </div>
                                    <div class="card-body p-4 text-center">
                                        <span class="small-label mb-2 d-block text-caramel"><?= htmlspecialchars($product['category_name']) ?></span>
                                        <h5 class="card-title font-serif fw-bold text-forest mb-3 text-truncate" title="<?= htmlspecialchars($product['name']) ?>"><?= htmlspecialchars($product['name']) ?></h5>
                                        <div class="fw-bold fs-5 text-dark mb-4">
                                            <?= number_format($product['price'], 0, ',', '.') ?>đ
                                        </div>
                                        <a href="index.php?route=products&action=detail&id=<?= $product['id'] ?>" class="btn btn-outline-forest w-100 rounded-pill py-2 fw-medium">CHI TIẾT</a>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <script>
                            function toggleFavorite(productId) {
                                fetch('index.php?route=favorites&action=toggle', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/x-www-form-urlencoded',
                                    },
                                    body: 'product_id=' + productId
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success && !data.is_favorited) {
                                        // Xóa khỏi UI
                                        document.getElementById('favorite-' + productId).remove();
                                        // Nếu danh sách trống thì reload trang
                                        if (document.querySelectorAll('.col[id^="favorite-"]').length === 0) {
                                            location.reload();
                                        }
                                    } else if (data.require_login) {
                                        alert(data.message);
                                        window.location.href = 'index.php?route=login';
                                    }
                                });
                            }
                        </script>
                    <?php endif; ?>
                    
                </div>
            </div>
        </div>
    </main>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
