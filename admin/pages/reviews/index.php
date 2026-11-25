<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="font-serif fw-bold text-dark mb-1">Quản lý đánh giá sản phẩm</h4>
        <p class="text-muted small mb-0">Theo dõi, kiểm duyệt và quản lý các phản hồi từ khách hàng</p>
    </div>
</div>

<!-- Thông báo Flash -->
<?php if ($success = get_flash('success')): ?>
    <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i> <?= e($success) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if ($error = get_flash('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
        <i class="fa-solid fa-triangle-exclamation me-2"></i> <?= e($error) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Stat Cards -->
<div class="row g-3 g-xl-4 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold text-uppercase">Tổng đánh giá</span>
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2">
                    <i class="fa-solid fa-comments"></i>
                </div>
            </div>
            <h4 class="fw-bold mb-0 text-dark"><?= number_format($totalReviewsCount) ?></h4>
            <small class="text-muted"><?= number_format($visibleCount) ?> đang hiển thị</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold text-uppercase">Điểm trung bình</span>
                <div class="bg-white bg-opacity-15 text-warning rounded-circle p-2">
                    <i class="fa-solid fa-star text-warning"></i>
                </div>
            </div>
            <h4 class="fw-bold mb-0 text-dark">
                <?= $avgRating ?> <span class="fs-6 text-muted">/ 5.0</span>
            </h4>
            <div class="small text-warning">
                <?php for($i = 1; $i <= 5; $i++): ?>
                    <i class="<?= $i <= round($avgRating) ? 'fa-solid' : 'fa-regular' ?> fa-star"></i>
                <?php endfor; ?>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold text-uppercase">5 Sao tuyệt đối</span>
                <div class="bg-success bg-opacity-10 text-success rounded-circle p-2">
                    <i class="fa-solid fa-thumbs-up"></i>
                </div>
            </div>
            <h4 class="fw-bold mb-0 text-success"><?= number_format((int)($stats['stars_5'] ?? 0)) ?></h4>
            <small class="text-muted"><?= $totalReviewsCount > 0 ? round(((int)($stats['stars_5'] ?? 0) / $totalReviewsCount) * 100, 1) : 0 ?>% tỷ lệ hài lòng</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted small fw-semibold text-uppercase">Đang bị ẩn</span>
                <div class="bg-danger bg-opacity-10 text-danger rounded-circle p-2">
                    <i class="fa-solid fa-eye-slash"></i>
                </div>
            </div>
            <h4 class="fw-bold mb-0 text-danger"><?= number_format($hiddenCount) ?></h4>
            <small class="text-muted">Cần rà soát hoặc vi phạm</small>
        </div>
    </div>
</div>

<!-- Bộ lọc tìm kiếm -->
<div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
    <form action="<?= e(admin_url('reviews')) ?>" method="GET" class="row g-3 align-items-end">
        <input type="hidden" name="page" value="reviews">

        <div class="col-12 col-md-5">
            <label class="form-label small fw-semibold text-muted mb-1">Tìm kiếm:</label>
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Tên khách hàng, tên món, nội dung đánh giá..." value="<?= e($search) ?>">
            </div>
        </div>

        <div class="col-6 col-md-2">
            <label class="form-label small fw-semibold text-muted mb-1">Số sao:</label>
            <select name="rating" class="form-select" onchange="this.form.submit()">
                <option value="all" <?= $ratingFilter === 'all' ? 'selected' : '' ?>>Tất cả sao</option>
                <option value="5" <?= $ratingFilter === '5' ? 'selected' : '' ?>>5 ⭐⭐⭐⭐⭐</option>
                <option value="4" <?= $ratingFilter === '4' ? 'selected' : '' ?>>4 ⭐⭐⭐⭐</option>
                <option value="3" <?= $ratingFilter === '3' ? 'selected' : '' ?>>3 ⭐⭐⭐</option>
                <option value="2" <?= $ratingFilter === '2' ? 'selected' : '' ?>>2 ⭐⭐</option>
                <option value="1" <?= $ratingFilter === '1' ? 'selected' : '' ?>>1 ⭐</option>
            </select>
        </div>

        <div class="col-6 col-md-2">
            <label class="form-label small fw-semibold text-muted mb-1">Trạng thái:</label>
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="all" <?= $statusFilter === 'all' ? 'selected' : '' ?>>Tất cả trạng thái</option>
                <option value="1" <?= $statusFilter === '1' ? 'selected' : '' ?>>Đang hiển thị</option>
                <option value="0" <?= $statusFilter === '0' ? 'selected' : '' ?>>Đang bị ẩn</option>
            </select>
        </div>

        <div class="col-12 col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-forest rounded-3 px-3 w-100">
                <i class="fa-solid fa-filter me-1"></i> Lọc
            </button>
            <a href="<?= e(admin_url('reviews')) ?>" class="btn btn-outline-secondary rounded-3" title="Đặt lại">
                <i class="fa-solid fa-rotate-left"></i>
            </a>
        </div>
    </form>
</div>

<!-- Reviews Table -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
    <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold text-dark mb-0 font-serif text-forest">Danh sách phản hồi & đánh giá</h5>
        <span class="badge bg-light text-dark border px-3 py-2 fw-medium">Tìm thấy: <?= number_format($totalFiltered) ?> đánh giá</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-4 py-3 fw-medium border-0" style="width: 220px;">Khách hàng</th>
                        <th class="py-3 fw-medium border-0" style="width: 200px;">Sản phẩm</th>
                        <th class="py-3 fw-medium border-0 text-center" style="width: 140px;">Đánh giá</th>
                        <th class="py-3 fw-medium border-0">Nội dung nhận xét</th>
                        <th class="py-3 fw-medium border-0 text-center" style="width: 130px;">Thời gian</th>
                        <th class="py-3 fw-medium border-0 text-center" style="width: 130px;">Trạng thái</th>
                        <th class="pe-4 py-3 fw-medium border-0 text-end" style="width: 120px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <?php if (empty($reviewsList)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fa-regular fa-comment-dots fs-1 d-block mb-3 text-secondary"></i>
                                Không tìm thấy đánh giá nào phù hợp với bộ lọc.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($reviewsList as $rv): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark"><?= e($rv['customer_name']) ?></div>
                                <small class="text-muted d-block"><?= e($rv['customer_email'] ?: $rv['customer_phone'] ?: 'Khách hàng #' . $rv['customer_id']) ?></small>
                                <?php if (!empty($rv['order_number'])): ?>
                                    <span class="badge bg-light text-secondary border mt-1" title="Mã đơn hàng">
                                        <i class="fa-solid fa-receipt me-1"></i>Đơn #<?= $rv['order_number'] ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="<?= e(product_image($rv['product_image'])) ?>" alt="<?= e($rv['product_name']) ?>" class="rounded-3 object-fit-cover me-2 border" width="40" height="40">
                                    <div>
                                        <span class="fw-semibold text-dark d-block"><?= e($rv['product_name']) ?></span>
                                        <a href="<?= e(url('product-detail', ['id' => $rv['product_id']])) ?>" target="_blank" class="small text-forest text-decoration-none">
                                            <i class="fa-solid fa-arrow-up-right-from-square small"></i> Xem món
                                        </a>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="text-warning fs-6">
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <i class="<?= $i <= $rv['rating'] ? 'fa-solid' : 'fa-regular' ?> fa-star"></i>
                                    <?php endfor; ?>
                                </div>
                                <span class="badge bg-warning bg-opacity-20 text-dark fw-bold rounded-pill px-2 py-0 small mt-1">
                                    <?= (int)$rv['rating'] ?> / 5 sao
                                </span>
                            </td>
                            <td>
                                <div class="p-2 rounded bg-light bg-opacity-50 text-dark" style="max-width: 400px; word-break: break-word;">
                                    <?= nl2br(e($rv['comment'] ?: '(Khách hàng không để lại bình luận chữ)')) ?>
                                </div>
                            </td>
                            <td class="text-center text-muted small">
                                <i class="fa-regular fa-clock me-1"></i>
                                <?= date('d/m/Y H:i', strtotime($rv['created_at'])) ?>
                            </td>
                            <td class="text-center">
                                <?php if ($rv['status'] == 1): ?>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 py-2">
                                        <i class="fa-solid fa-circle-check me-1"></i> Hiển thị
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 rounded-pill px-3 py-2">
                                        <i class="fa-solid fa-eye-slash me-1"></i> Đã ẩn
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="pe-4 text-end">
                                <div class="d-inline-flex gap-1">
                                    <!-- Toggle Hiện/Ẩn -->
                                    <a href="<?= e(admin_url('reviews', ['act' => 'toggle_status', 'id' => $rv['id']])) ?>" 
                                       class="btn btn-sm <?= $rv['status'] == 1 ? 'btn-outline-warning' : 'btn-outline-success' ?> rounded-3" 
                                       title="<?= $rv['status'] == 1 ? 'Ẩn đánh giá' : 'Hiện đánh giá' ?>">
                                        <i class="fa-solid <?= $rv['status'] == 1 ? 'fa-eye-slash' : 'fa-eye' ?>"></i>
                                    </a>

                                    <!-- Xóa đánh giá -->
                                    <form action="<?= e(admin_url('reviews', ['act' => 'delete', 'id' => $rv['id']])) ?>" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đánh giá này vĩnh viễn không?');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-3" title="Xóa đánh giá">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Phân trang -->
        <?php if ($totalPages > 1): ?>
            <div class="card-footer bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <small class="text-muted">Trang <?= $currentPageNum ?> / <?= $totalPages ?></small>
                <ul class="pagination pagination-sm mb-0">
                    <?php 
                        $qParams = [];
                        if ($search !== '') $qParams['search'] = $search;
                        if ($ratingFilter !== 'all') $qParams['rating'] = $ratingFilter;
                        if ($statusFilter !== 'all') $qParams['status'] = $statusFilter;
                    ?>
                    <li class="page-item <?= $currentPageNum <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= e(admin_url('reviews', array_merge($qParams, ['p' => $currentPageNum - 1]))) ?>"><i class="fa-solid fa-chevron-left"></i></a>
                    </li>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i === $currentPageNum ? 'active' : '' ?>">
                            <a class="page-link" href="<?= e(admin_url('reviews', array_merge($qParams, ['p' => $i]))) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $currentPageNum >= $totalPages ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= e(admin_url('reviews', array_merge($qParams, ['p' => $currentPageNum + 1]))) ?>"><i class="fa-solid fa-chevron-right"></i></a>
                    </li>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</div>
