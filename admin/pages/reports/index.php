<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="font-serif fw-bold text-dark mb-1">Báo cáo & Thống kê</h4>
        <p class="text-muted small mb-0">Báo cáo hiệu quả kinh doanh của VAA THÉ</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-forest rounded-pill px-4" onclick="window.print()"><i class="fa-solid fa-download me-2"></i> Xuất báo cáo</button>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-medium">DOANH THU HOÀN THÀNH</span>
                <div class="bg-success bg-opacity-10 text-success rounded-circle p-2">
                    <i class="fa-solid fa-money-bill-wave"></i>
                </div>
            </div>
            <h4 class="fw-bold mb-1 text-forest"><?= money($totalRevenue) ?></h4>
            <small class="text-muted">Tổng doanh thu thực nhận</small>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-medium">ĐƠN HÀNG THÀNH CÔNG</span>
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2">
                    <i class="fa-solid fa-receipt"></i>
                </div>
            </div>
            <h4 class="fw-bold mb-1 text-dark"><?= $totalOrders ?></h4>
            <small class="text-muted">Đơn đã giao thành công</small>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-medium">SẢN PHẨM ĐÃ BÁN</span>
                <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-2">
                    <i class="fa-solid fa-mug-hot"></i>
                </div>
            </div>
            <h4 class="fw-bold mb-1 text-dark"><?= $totalSold ?> ly</h4>
            <small class="text-muted">Tổng ly trà sữa bán ra</small>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-medium">KHÁCH HÀNG</span>
                <div class="bg-info bg-opacity-10 text-info rounded-circle p-2">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            <h4 class="fw-bold mb-1 text-dark"><?= $totalCustomers ?></h4>
            <small class="text-muted">Tài khoản thành viên</small>
        </div>
    </div>
</div>

<!-- Top Products -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
    <div class="card-header bg-white border-0 py-3 px-4">
        <h5 class="fw-bold text-dark mb-0 font-serif text-forest">Top sản phẩm bán chạy nhất</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-4 py-3 fw-medium border-0">Sản phẩm</th>
                        <th class="py-3 fw-medium border-0 text-center">Số lượng bán</th>
                        <th class="pe-4 py-3 fw-medium border-0 text-end">Doanh số</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <?php if (empty($topProducts)): ?>
                        <tr><td colspan="3" class="text-center py-4 text-muted">Chưa có đủ dữ liệu đơn hàng.</td></tr>
                    <?php else: ?>
                        <?php foreach ($topProducts as $tp): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <img src="<?= e(product_image($tp['image'])) ?>" alt="<?= e($tp['name']) ?>" class="rounded-3 object-fit-cover me-3" width="40" height="40">
                                    <span class="fw-bold text-dark"><?= e($tp['name']) ?></span>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1"><?= (int)$tp['sold_count'] ?> ly</span>
                            </td>
                            <td class="pe-4 text-end fw-bold text-forest font-monospace"><?= money($tp['total_sales']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
