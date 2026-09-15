<link rel="stylesheet" href="assets/css/inventory.css">

<div class="d-flex justify-content-between align-items-center mb-2">
    <div>
        <h4 class="font-serif fw-bold text-dark mb-1">QUẢN LÝ NGUYÊN LIỆU</h4>
        <p class="text-muted fst-italic small mb-0">"The ingredients behind every good cup."</p>
    </div>
    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
    <a href="admin.php?route=inventory&action=create" class="btn btn-forest fw-medium px-4">
        <i class="fa-solid fa-plus me-2"></i> Thêm nguyên liệu
    </a>
    <?php endif; ?>
</div>

<hr class="my-4" style="border-color: rgba(0,0,0,0.05);">

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card-inv">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon-inv bg-primary bg-opacity-10 text-primary">
                    <i class="fa-solid fa-cubes"></i>
                </div>
            </div>
            <h6 class="text-muted fw-semibold mb-1">Tổng nguyên liệu</h6>
            <h3 class="fw-bold mb-0 text-dark"><?= $stats['total'] ?></h3>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card-inv">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon-inv bg-success bg-opacity-10 text-success">
                    <i class="fa-solid fa-check"></i>
                </div>
            </div>
            <h6 class="text-muted fw-semibold mb-1">Còn hàng</h6>
            <h3 class="fw-bold mb-0 text-dark"><?= $stats['in_stock'] ?></h3>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card-inv">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon-inv bg-warning bg-opacity-10 text-warning">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
            </div>
            <h6 class="text-muted fw-semibold mb-1">Sắp hết</h6>
            <h3 class="fw-bold mb-0 text-dark"><?= $stats['low_stock'] ?></h3>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card-inv">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="stat-icon-inv bg-danger bg-opacity-10 text-danger">
                    <i class="fa-solid fa-circle-xmark"></i>
                </div>
            </div>
            <h6 class="text-muted fw-semibold mb-1">Hết hàng</h6>
            <h3 class="fw-bold mb-0 text-dark"><?= $stats['out_of_stock'] ?></h3>
        </div>
    </div>
</div>

<!-- Search & Filter -->
<div class="filter-section">
    <div class="row g-3">
        <div class="col-12 col-md-4">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-search"></i></span>
                <input type="text" class="form-control border-start-0 shadow-none ps-0" placeholder="Tìm tên nguyên liệu...">
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <select class="form-select shadow-none text-muted">
                <option value="">Tất cả đơn vị</option>
                <option value="kg">kg</option>
                <option value="g">g</option>
                <option value="L">L</option>
                <option value="ml">ml</option>
                <option value="cái">cái</option>
            </select>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <select class="form-select shadow-none text-muted">
                <option value="">Tất cả trạng thái</option>
                <option value="instock">Còn hàng</option>
                <option value="low">Sắp hết</option>
                <option value="out">Hết hàng</option>
            </select>
        </div>
        <div class="col-12 col-md-2 d-flex gap-2">
            <button class="btn btn-forest w-100"><i class="fa-solid fa-filter me-1"></i> Lọc</button>
            <button class="btn btn-light border w-100"><i class="fa-solid fa-rotate-right"></i></button>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="d-flex gap-2 mb-4">
    <a href="admin.php?route=inventory_import" class="btn btn-outline-forest fw-medium">
        <i class="fa-solid fa-arrow-down me-1"></i> Nhập kho
    </a>
    <a href="admin.php?route=inventory_export" class="btn btn-outline-danger fw-medium">
        <i class="fa-solid fa-arrow-up me-1"></i> Xuất kho
    </a>
</div>

<!-- Inventory Table -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-inventory table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4 py-3 rounded-top-left" style="width: 60px;">STT</th>
                        <th class="py-3">Nguyên liệu</th>
                        <th class="py-3">Đơn vị</th>
                        <th class="py-3 text-end">Số lượng tồn</th>
                        <th class="py-3 text-end">Mức tối thiểu</th>
                        <th class="py-3 text-end">Giá nhập</th>
                        <th class="py-3 text-center">Trạng thái</th>
                        <th class="pe-4 py-3 text-end rounded-top-right">Hành động</th>
                    </tr>
                </thead>
                <tbody class="border-top-0 bg-white">
                    <?php if(empty($ingredients)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">Kho trống. Chưa có nguyên liệu nào.</td>
                        </tr>
                    <?php else: ?>
                        <?php 
                        $stt = 1;
                        foreach($ingredients as $item): 
                            $qty = floatval($item['quantity']);
                            $min = floatval($item['min_quantity']);
                            
                            $statusClass = 'status-instock';
                            $statusText = 'Còn hàng';
                            
                            if ($qty <= 0) {
                                $statusClass = 'status-out';
                                $statusText = 'Hết hàng';
                            } elseif ($qty <= $min) {
                                $statusClass = 'status-low';
                                $statusText = 'Sắp hết';
                            }
                        ?>
                        <tr>
                            <td class="ps-4 text-muted"><?= str_pad($stt++, 2, '0', STR_PAD_LEFT) ?></td>
                            <td>
                                <div class="fw-bold text-dark"><?= htmlspecialchars($item['ingredient_name']) ?></div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border px-2 py-1"><?= htmlspecialchars($item['unit']) ?></span>
                            </td>
                            <td class="text-end fw-bold <?= $qty <= 0 ? 'text-danger' : ($qty <= $min ? 'text-warning' : 'text-forest') ?>">
                                <?= number_format($qty, strpos($qty, '.') !== false ? 2 : 0) ?>
                            </td>
                            <td class="text-end text-muted">
                                <?= number_format($min, strpos($min, '.') !== false ? 2 : 0) ?>
                            </td>
                            <td class="text-end fw-medium">
                                <?= number_format($item['price'], 0, ',', '.') ?> ₫
                            </td>
                            <td class="text-center">
                                <span class="status-badge <?= $statusClass ?>"><i class="fa-solid <?= $qty<=0 ? 'fa-xmark' : ($qty<=$min ? 'fa-exclamation' : 'fa-check') ?> me-1"></i> <?= $statusText ?></span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                    <a href="admin.php?route=inventory&action=edit&id=<?= $item['id'] ?>" class="btn btn-sm btn-light border shadow-none text-primary" title="Sửa">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <form action="admin.php?route=inventory&action=delete" method="POST" class="m-0" onsubmit="return confirm('Bạn có chắc muốn xóa nguyên liệu này?');">
                                        <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-light border shadow-none text-danger" title="Xóa">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
