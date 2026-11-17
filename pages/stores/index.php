<?php
// pages/stores/index.php — Hệ thống cửa hàng VAA THÉ chuẩn gốc

if (!empty($store)) {
    require __DIR__ . '/detail.php';
    return;
}
?>
<!-- Hero Section -->
<section class="store-hero py-5 bg-cream">
    <div class="container text-center py-4">
        <p class="text-uppercase fw-bold mb-2 text-caramel small letter-spacing-wide">OUR STORES</p>
        <h1 class="font-serif fw-bold display-5 text-forest">Tìm VAA THÉ gần bạn</h1>
        <p class="text-muted mx-auto" style="max-width: 600px;">Ghé thăm không gian VAA THÉ và thưởng thức ly trà yêu thích của bạn trong không gian tối giản, tinh tế.</p>
        
        <!-- Search Bar -->
        <div class="row justify-content-center mt-4">
            <div class="col-11 col-md-8 col-lg-6">
                <div class="bg-white p-2 rounded-pill shadow-sm d-flex align-items-center border">
                    <input type="text" id="store-search-input" class="form-control border-0 shadow-none ps-3" placeholder="Tìm theo tên đường, quận...">
                    <select id="store-status-select" class="form-select border-0 shadow-none w-auto pe-4">
                        <option value="all">Tất cả</option>
                        <option value="open">Đang mở cửa</option>
                    </select>
                    <button class="btn btn-forest rounded-circle p-2 ms-2" style="width: 42px; height: 42px;"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="py-5">
    <div class="container py-4">
        <div class="row g-5">
            <!-- Cột trái: Danh sách cửa hàng -->
            <div class="col-12 col-lg-5">
                <div class="d-flex justify-content-between align-items-end mb-4">
                    <h4 class="font-serif fw-bold text-forest mb-0">Danh sách Cửa hàng</h4>
                    <span class="text-muted small" id="store-count"><?= count($stores) ?> cửa hàng</span>
                </div>
                
                <div class="d-flex flex-column gap-4" id="store-list">
                    <?php foreach ($stores as $store): ?>
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden p-4 store-card" style="cursor: pointer;" onclick="window.location.href='<?= e(url('stores', ['id' => $store['id']])) ?>'" data-status="<?= $store['status'] ?>" data-name="<?= e(mb_strtolower($store['name'] . ' ' . $store['address'])) ?>">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="font-serif fw-bold text-forest mb-0"><?= e($store['name']) ?></h5>
                            <?php if ($store['status'] === 'open'): ?>
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1 small fw-medium">Đang mở cửa</span>
                            <?php else: ?>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-1 small fw-medium">Đã đóng cửa</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="text-muted small mt-2 d-flex align-items-center gap-2">
                            <i class="fa-solid fa-location-dot text-caramel"></i>
                            <span><?= e($store['address']) ?></span>
                        </div>
                        <div class="text-muted small mt-1 d-flex align-items-center gap-2">
                            <i class="fa-solid fa-phone text-caramel"></i>
                            <span><?= e($store['phone']) ?></span>
                        </div>
                        <div class="text-muted small mt-1 mb-3 d-flex align-items-center gap-2">
                            <i class="fa-regular fa-clock text-caramel"></i>
                            <span><?= e($store['open_time']) ?> - <?= e($store['close_time']) ?></span>
                        </div>

                        <div class="d-flex flex-wrap gap-1 mb-3">
                            <?php foreach ($store['amenities'] as $am): ?>
                                <span class="badge bg-light text-muted border small"><?= e($am) ?></span>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="d-flex gap-2" onclick="event.stopPropagation();">
                            <a href="<?= e(url('stores', ['id' => $store['id']])) ?>" class="btn btn-outline-forest rounded-pill flex-grow-1 py-2 fw-medium text-center">
                                Xem chi tiết
                            </a>
                            <a href="<?= e($store['map_url'] ?? ('https://maps.google.com/?q=' . urlencode($store['address']))) ?>" target="_blank" class="btn btn-forest rounded-circle p-2 d-flex align-items-center justify-content-center text-white" style="width: 42px; height: 42px;" title="Chỉ đường">
                                <i class="fa-solid fa-directions"></i>
                            </a>
                            <a href="tel:<?= e($store['phone']) ?>" class="btn btn-light border rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;" title="Hotline">
                                <i class="fa-solid fa-phone text-forest"></i>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Cột phải: Bản đồ minh họa -->
            <div class="col-12 col-lg-7 d-none d-lg-block">
                <div class="store-map-container shadow-sm rounded-4 overflow-hidden border sticky-top" style="top: 100px; height: 600px; background: #EFE8DA; display: flex; align-items: center; justify-content: center; position: relative;">
                    <div class="text-center p-5 bg-white bg-opacity-90 rounded-4 shadow-sm mx-4" style="max-width: 420px;">
                        <div class="bg-forest text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center shadow" style="width: 70px; height: 70px;">
                            <i class="fa-solid fa-map-location-dot fs-2"></i>
                        </div>
                        <h4 class="font-serif fw-bold text-forest mb-2">Bản đồ Cửa hàng</h4>
                        <p class="text-muted small mb-3">Tương tác trực tiếp trên bản đồ để tìm chi nhánh VAA THÉ gần bạn nhất.</p>
                        <a href="https://maps.google.com/?q=VAA+THE" target="_blank" class="btn btn-forest rounded-pill px-4 fw-medium shadow-sm">
                            <i class="fa-solid fa-diamond-turn-right me-2"></i>Mở Google Maps
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('store-search-input');
    const statusSelect = document.getElementById('store-status-select');
    const storeCards = document.querySelectorAll('.store-card');
    const storeCount = document.getElementById('store-count');

    function filterStores() {
        const query = searchInput.value.trim().toLowerCase();
        const status = statusSelect.value;
        let visibleCount = 0;

        storeCards.forEach(card => {
            const name = card.getAttribute('data-name');
            const cardStatus = card.getAttribute('data-status');
            const matchesQuery = query === '' || name.includes(query);
            const matchesStatus = status === 'all' || cardStatus === status;

            if (matchesQuery && matchesStatus) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        if (storeCount) {
            storeCount.textContent = `${visibleCount} cửa hàng`;
        }
    }

    searchInput?.addEventListener('input', filterStores);
    statusSelect?.addEventListener('change', filterStores);
});
</script>
