<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống Cửa hàng - VAA THÉ</title>
    
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
        // JS để active nav-link "Cửa hàng"
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.nav-link').forEach(link => {
                if(link.textContent.trim() === 'Cửa hàng') {
                    link.classList.add('active');
                }
            });
        });
    </script>

    <!-- Hero Section -->
    <section class="store-hero">
        <div class="container position-relative">
            <p class="text-uppercase fw-bold mb-2 text-brown" style="letter-spacing: 3px; font-size: 0.85rem;">Our Stores</p>
            <h1 class="font-serif fw-bold">Tìm VAA THÉ gần bạn</h1>
            <p class="mt-3">Ghé thăm không gian VAA THÉ và thưởng thức<br>ly trà yêu thích của bạn.</p>
            
            <!-- Search Bar Overlapping -->
            <div class="row justify-content-center mt-5 mb-n5 position-absolute w-100" style="bottom: -110px; left: 0;">
                <div class="col-11 col-md-8 col-lg-6">
                    <div class="store-search-container d-flex">
                        <input type="text" class="form-control store-search-input" placeholder="Tìm theo tên đường, quận...">
                        <select class="form-select store-search-select" style="max-width: 150px;">
                            <option value="all">Tất cả</option>
                            <option value="open">Đang mở cửa</option>
                            <option value="near">Gần bạn</option>
                        </select>
                        <button class="btn btn-store-search ms-2 px-4"><i class="fa-solid fa-magnifying-glass"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-5" style="margin-top: 60px;">
        <div class="container py-4">
            <div class="row g-5">
                
                <!-- Cột trái: Danh sách cửa hàng -->
                <div class="col-12 col-lg-5">
                    <div class="d-flex justify-content-between align-items-end mb-4">
                        <h4 class="font-serif fw-bold text-forest mb-0">Danh sách Cửa hàng</h4>
                        <span class="text-muted small"><?= count($stores) ?> cửa hàng</span>
                    </div>
                    
                    <div class="store-list-scroll pe-2">
                        <?php if(empty($stores)): ?>
                            <div class="text-center py-5 text-muted">
                                <i class="fa-solid fa-store-slash fs-1 mb-3 opacity-50"></i>
                                <p>Không tìm thấy cửa hàng nào phù hợp.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach($stores as $store): ?>
                            <div class="store-card" onclick="window.location.href='stores.php?id=<?= $store['id'] ?>'">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="store-card-title font-serif fw-bold mb-0"><?= $store['name'] ?></h5>
                                    <?php if($store['status'] === 'open'): ?>
                                        <span class="badge-status-open small">Đang mở cửa</span>
                                    <?php else: ?>
                                        <span class="badge-status-closed small">Đã đóng cửa</span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="store-info-item mt-3">
                                    <i class="fa-solid fa-location-dot"></i>
                                    <span><?= $store['address'] ?></span>
                                </div>
                                <div class="store-info-item">
                                    <i class="fa-solid fa-phone"></i>
                                    <span><?= $store['phone'] ?></span>
                                </div>
                                <div class="store-info-item mb-4">
                                    <i class="fa-regular fa-clock"></i>
                                    <span><?= $store['open_time'] ?> - <?= $store['close_time'] ?></span>
                                </div>
                                
                                <div class="d-flex gap-2">
                                    <a href="stores.php?id=<?= $store['id'] ?>" class="btn btn-outline-store flex-grow-1" onclick="event.stopPropagation();">
                                        Xem chi tiết
                                    </a>
                                    <button class="btn btn-store-search px-4" onclick="event.stopPropagation();" title="Chỉ đường">
                                        <i class="fa-solid fa-directions"></i>
                                    </button>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Cột phải: Bản đồ -->
                <div class="col-12 col-lg-7 d-none d-lg-block">
                    <div class="store-map-container shadow-sm">
                        <!-- Giả lập map bằng pattern CSS -->
                        <div class="map-placeholder-overlay">
                            <div class="bg-white p-4 rounded-circle shadow mb-3" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                                <i class="fa-solid fa-map-location-dot fs-1 text-forest"></i>
                            </div>
                            <h4 class="font-serif fw-bold mb-2">Bản đồ Cửa hàng</h4>
                            <p class="text-muted text-center max-w-sm">Tương tác trực tiếp trên bản đồ để tìm chi nhánh VAA THÉ gần bạn nhất.</p>
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
