<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $store['name'] ?> - VAA THÉ</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/store.css">
</head>
<body>

    <!-- Header (Navbar) -->
    <?php include __DIR__ . '/../../partials/navbar_old.php'; ?>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.nav-link').forEach(link => {
                if(link.textContent.trim() === 'Cửa hàng') {
                    link.classList.add('active');
                }
            });
        });
    </script>

    <!-- Main Content -->
    <section class="py-5 bg-cream">
        <div class="container py-4">
            
            <div class="mb-4">
                <a href="stores.php" class="text-decoration-none text-brown fw-medium">
                    <i class="fa-solid fa-arrow-left me-2"></i> Quay lại danh sách
                </a>
            </div>

            <!-- Big Image Header -->
            <div class="store-detail-header shadow-sm">
                <img src="<?= $store['image'] ?>" alt="<?= $store['name'] ?>" class="store-detail-img">
                <div class="store-detail-overlay">
                    <div class="d-flex align-items-center mb-3">
                        <?php if($store['status'] === 'open'): ?>
                            <span class="badge bg-success bg-opacity-25 text-white border border-success px-3 py-2 rounded-pill me-3">Đang mở cửa</span>
                        <?php else: ?>
                            <span class="badge bg-secondary bg-opacity-50 text-white px-3 py-2 rounded-pill me-3">Đã đóng cửa</span>
                        <?php endif; ?>
                    </div>
                    <h1 class="font-serif fw-bold text-white mb-2" style="font-size: 3.5rem;"><?= $store['name'] ?></h1>
                    <p class="fs-5 mb-0 opacity-75"><i class="fa-solid fa-location-dot me-2"></i> <?= $store['address'] ?></p>
                </div>
            </div>

            <div class="row g-5">
                <!-- Thông tin chi tiết -->
                <div class="col-12 col-lg-5">
                    <div class="bg-white p-5 rounded-4 shadow-sm border border-secondary border-opacity-10 h-100">
                        <h4 class="font-serif fw-bold text-forest mb-4">Thông tin liên hệ</h4>
                        
                        <div class="d-flex align-items-start mb-4">
                            <div class="bg-beige text-brown rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 45px; height: 45px;">
                                <i class="fa-solid fa-phone fs-5"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1 text-dark">Hotline</h6>
                                <p class="text-muted mb-0 fs-5"><?= $store['phone'] ?></p>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-start mb-4">
                            <div class="bg-beige text-brown rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 45px; height: 45px;">
                                <i class="fa-regular fa-clock fs-5"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1 text-dark">Giờ mở cửa</h6>
                                <p class="text-muted mb-0 fs-5"><?= $store['open_time'] ?> - <?= $store['close_time'] ?> hàng ngày</p>
                            </div>
                        </div>

                        <hr class="my-5 text-secondary opacity-25">

                        <h4 class="font-serif fw-bold text-forest mb-4">Tiện ích tại quán</h4>
                        <div class="d-flex flex-wrap">
                            <?php foreach($store['amenities'] as $amenity): ?>
                                <div class="amenity-badge">
                                    <?php 
                                        $icon = 'fa-check';
                                        if(strpos(strtolower($amenity), 'wi-fi') !== false) $icon = 'fa-wifi';
                                        if(strpos(strtolower($amenity), 'điều hòa') !== false) $icon = 'fa-snowflake';
                                        if(strpos(strtolower($amenity), 'chỗ ngồi') !== false) $icon = 'fa-chair';
                                        if(strpos(strtolower($amenity), 'thanh toán') !== false) $icon = 'fa-qrcode';
                                        if(strpos(strtolower($amenity), 'xe ô tô') !== false) $icon = 'fa-car';
                                    ?>
                                    <i class="fa-solid <?= $icon ?>"></i> <?= $amenity ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="mt-5 pt-3">
                            <button class="btn btn-store-search w-100 py-3 fs-5"><i class="fa-solid fa-directions me-2"></i> Chỉ đường đến đây</button>
                        </div>
                    </div>
                </div>

                <!-- Bản đồ -->
                <div class="col-12 col-lg-7">
                    <div class="store-map-container shadow-sm h-100 min-vh-50">
                        <div class="map-placeholder-overlay" style="background-color: #f0ede6;">
                            <div class="text-center p-4">
                                <i class="fa-solid fa-map-location-dot text-forest mb-3" style="font-size: 4rem;"></i>
                                <h3 class="font-serif fw-bold text-dark">Google Maps</h3>
                                <p class="text-muted text-center max-w-sm mb-4">Bản đồ chi tiết của <?= $store['name'] ?> sẽ được nhúng tại đây.</p>
                                <button class="btn btn-outline-store px-4 bg-white">Mở trong Google Maps <i class="fa-solid fa-arrow-up-right-from-square ms-2 small"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Footer -->
    <?php include __DIR__ . '/../../layouts/footer.html'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
