<?php
// pages/stores/detail.php — Chi tiết cửa hàng VAA THÉ chuẩn gốc

if (empty($store)) {
    redirect(url('stores'));
}
?>
<!-- Main Content -->
<section class="py-5 bg-cream">
    <div class="container py-4">
        
        <div class="mb-4">
            <a href="<?= e(url('stores')) ?>" class="text-decoration-none text-brown fw-medium">
                <i class="fa-solid fa-arrow-left me-2"></i> Quay lại danh sách
            </a>
        </div>

        <!-- Big Image Header -->
        <div class="store-detail-header shadow-sm position-relative overflow-hidden rounded-4 mb-5">
            <img src="<?= e($store['image']) ?>" alt="<?= e($store['name']) ?>" class="store-detail-img w-100" style="height: 400px; object-fit: cover;">
            <div class="store-detail-overlay position-absolute bottom-0 start-0 end-0 p-4 p-md-5" style="background: linear-gradient(to top, rgba(24, 35, 29, 0.85), transparent);">
                <div class="d-flex align-items-center mb-3">
                    <?php if ($store['status'] === 'open'): ?>
                        <span class="badge bg-success bg-opacity-25 text-white border border-success px-3 py-2 rounded-pill me-3">Đang mở cửa</span>
                    <?php else: ?>
                        <span class="badge bg-secondary bg-opacity-50 text-white px-3 py-2 rounded-pill me-3">Đã đóng cửa</span>
                    <?php endif; ?>
                </div>
                <h1 class="font-serif fw-bold text-white mb-2 display-5"><?= e($store['name']) ?></h1>
                <p class="fs-5 mb-0 text-white text-opacity-75"><i class="fa-solid fa-location-dot me-2"></i> <?= e($store['address']) ?></p>
            </div>
        </div>

        <div class="row g-5">
            <!-- Thông tin chi tiết (Cột trái) -->
            <div class="col-12 col-lg-5">
                <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm border border-secondary border-opacity-10 h-100">
                    <h4 class="font-serif fw-bold text-forest mb-4">Thông tin liên hệ</h4>
                    
                    <div class="d-flex align-items-start mb-4">
                        <div class="bg-beige text-brown rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 48px; height: 48px;">
                            <i class="fa-solid fa-phone fs-5 text-forest"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1 text-dark">Hotline</h6>
                            <p class="text-muted mb-0 fs-5">
                                <a href="tel:<?= e($store['phone']) ?>" class="text-decoration-none text-forest fw-medium"><?= e($store['phone']) ?></a>
                            </p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-start mb-4">
                        <div class="bg-beige text-brown rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 48px; height: 48px;">
                            <i class="fa-regular fa-clock fs-5 text-forest"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1 text-dark">Giờ mở cửa</h6>
                            <p class="text-muted mb-0 fs-5"><?= e($store['open_time']) ?> - <?= e($store['close_time']) ?> hàng ngày</p>
                        </div>
                    </div>

                    <hr class="my-4 text-secondary opacity-25">

                    <h4 class="font-serif fw-bold text-forest mb-4">Tiện ích tại quán</h4>
                    <div class="d-flex flex-wrap gap-2">
                        <?php foreach ($store['amenities'] as $amenity): ?>
                            <div class="amenity-badge">
                                <?php 
                                    $icon = 'fa-check';
                                    $lower = mb_strtolower($amenity);
                                    if (strpos($lower, 'wi-fi') !== false || strpos($lower, 'wifi') !== false) $icon = 'fa-wifi';
                                    elseif (strpos($lower, 'điều hòa') !== false || strpos($lower, 'máy lạnh') !== false) $icon = 'fa-snowflake';
                                    elseif (strpos($lower, 'chỗ ngồi') !== false || strpos($lower, 'ghế') !== false) $icon = 'fa-chair';
                                    elseif (strpos($lower, 'thanh toán') !== false || strpos($lower, 'qr') !== false) $icon = 'fa-qrcode';
                                    elseif (strpos($lower, 'xe') !== false || strpos($lower, 'ô tô') !== false) $icon = 'fa-car';
                                ?>
                                <i class="fa-solid <?= $icon ?> me-2 text-forest"></i> <?= e($amenity) ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="mt-5 pt-3">
                        <a href="<?= e($store['map_url'] ?? ('https://maps.google.com/?q=' . urlencode($store['address']))) ?>" target="_blank" class="btn btn-forest w-100 py-3 fs-5 rounded-pill text-center text-decoration-none shadow-sm fw-medium d-block">
                            <i class="fa-solid fa-directions me-2"></i> Chỉ đường đến đây
                        </a>
                    </div>
                </div>
            </div>

            <!-- Bản đồ (Cột phải) -->
            <div class="col-12 col-lg-7">
                <div class="store-map-container shadow-sm rounded-4 h-100 min-vh-50 overflow-hidden position-relative border" style="min-height: 520px; background: #EFE8DA;">
                    <div class="map-placeholder-overlay position-absolute top-0 bottom-0 start-0 end-0 d-flex flex-column align-items-center justify-content-center p-4">
                        <div class="text-center p-4 bg-white bg-opacity-90 rounded-4 shadow-sm mx-4" style="max-width: 440px;">
                            <div class="bg-forest text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center shadow" style="width: 70px; height: 70px;">
                                <i class="fa-solid fa-map-location-dot fs-2"></i>
                            </div>
                            <h3 class="font-serif fw-bold text-forest mb-2"><?= e($store['name']) ?></h3>
                            <p class="text-muted text-center mb-4 small"><i class="fa-solid fa-location-dot me-1 text-caramel"></i> <?= e($store['address']) ?></p>
                            <a href="<?= e($store['map_url'] ?? ('https://maps.google.com/?q=' . urlencode($store['address']))) ?>" target="_blank" class="btn btn-outline-forest px-4 py-2 rounded-pill bg-white fw-medium shadow-sm">
                                Mở trong Google Maps <i class="fa-solid fa-arrow-up-right-from-square ms-2 small"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
