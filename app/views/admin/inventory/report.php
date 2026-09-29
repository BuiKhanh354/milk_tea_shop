<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="font-serif fw-bold text-dark mb-1">BÁO CÁO TỒN KHO</h4>
        <p class="text-muted fst-italic small mb-0">"Tổng quan tình trạng nguyên liệu trong hệ thống."</p>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card-inv">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon-inv bg-primary bg-opacity-10 text-primary">
                    <i class="fa-solid fa-cubes"></i>
                </div>
            </div>
            <h6 class="text-muted small fw-medium text-uppercase letter-spacing-1 mb-1">Tổng Nguyên Liệu</h6>
            <h3 class="fw-bold mb-0 text-dark"><?= $stats['total_items'] ?></h3>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card-inv">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon-inv bg-success bg-opacity-10 text-success">
                    <i class="fa-solid fa-money-bill-wave"></i>
                </div>
            </div>
            <h6 class="text-muted small fw-medium text-uppercase letter-spacing-1 mb-1">Tổng Giá Trị Tồn Kho</h6>
            <h3 class="fw-bold mb-0 text-dark"><?= number_format($stats['total_value'], 0, ',', '.') ?> đ</h3>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card-inv">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon-inv bg-warning bg-opacity-10 text-warning">
                    <i class="fa-solid fa-exclamation-triangle"></i>
                </div>
            </div>
            <h6 class="text-muted small fw-medium text-uppercase letter-spacing-1 mb-1">Sắp Hết Hàng</h6>
            <h3 class="fw-bold mb-0 text-dark"><?= $stats['low_stock'] ?></h3>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card-inv">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon-inv bg-danger bg-opacity-10 text-danger">
                    <i class="fa-solid fa-xmark-circle"></i>
                </div>
            </div>
            <h6 class="text-muted small fw-medium text-uppercase letter-spacing-1 mb-1">Hết Hàng</h6>
            <h3 class="fw-bold mb-0 text-dark"><?= $stats['out_of_stock'] ?></h3>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-4 text-forest">Chi tiết Giá trị Tồn kho</h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 fw-medium border-0 rounded-top-left">Nguyên liệu</th>
                        <th class="py-3 fw-medium border-0 text-end">Tồn kho</th>
                        <th class="py-3 fw-medium border-0 text-end">Giá nhập tham khảo</th>
                        <th class="pe-4 py-3 fw-medium border-0 rounded-top-right text-end">Thành tiền (Ước tính)</th>
                    </tr>
                </thead>
                <tbody class="border-top-0 bg-white">
                    <?php if(empty($ingredients)): ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">Chưa có dữ liệu.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($ingredients as $ing): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark"><?= htmlspecialchars($ing['ingredient_name']) ?></div>
                                <span class="badge bg-light text-dark border px-2 py-1 mt-1"><?= htmlspecialchars($ing['unit']) ?></span>
                            </td>
                            <td class="text-end fw-medium <?= $ing['quantity'] <= 0 ? 'text-danger' : '' ?>">
                                <?= floatval($ing['quantity']) ?>
                            </td>
                            <td class="text-end text-muted">
                                <?= number_format($ing['price'], 0, ',', '.') ?> đ
                            </td>
                            <td class="pe-4 text-end fw-bold text-forest">
                                <?= number_format($ing['quantity'] * $ing['price'], 0, ',', '.') ?> đ
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
