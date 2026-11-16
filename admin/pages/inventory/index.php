<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="font-serif fw-bold text-dark mb-1">QUẢN LÝ NGUYÊN LIỆU</h4>
        <p class="text-muted fst-italic small mb-0">"The ingredients behind every good cup."</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= e(admin_url('inventory_import')) ?>" class="btn btn-outline-forest rounded-pill px-3 fw-medium">
            <i class="fa-solid fa-box-open me-1"></i> Nhập kho
        </a>
        <a href="<?= e(admin_url('inventory_export')) ?>" class="btn btn-outline-danger rounded-pill px-3 fw-medium">
            <i class="fa-solid fa-truck-fast me-1"></i> Xuất kho
        </a>
        <button type="button" class="btn btn-forest rounded-pill fw-medium px-3" data-bs-toggle="modal" data-bs-target="#addIngredientModal">
            <i class="fa-solid fa-plus me-1"></i> Thêm nguyên liệu
        </button>
    </div>
</div>

<?php if ($successMsg = get_flash('success')): ?>
    <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
        <i class="fa-solid fa-check-circle me-2"></i> <?= e($successMsg) ?>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if ($errorMsg = get_flash('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
        <i class="fa-solid fa-triangle-exclamation me-2"></i> <?= e($errorMsg) ?>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <div class="d-flex align-items-center mb-2">
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-2">
                    <i class="fa-solid fa-cubes"></i>
                </div>
                <span class="text-muted small fw-medium">Tổng nguyên liệu</span>
            </div>
            <h3 class="fw-bold mb-0 text-dark"><?= $stats['total'] ?></h3>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <div class="d-flex align-items-center mb-2">
                <div class="bg-success bg-opacity-10 text-success rounded-circle p-2 me-2">
                    <i class="fa-solid fa-check"></i>
                </div>
                <span class="text-muted small fw-medium">Còn hàng</span>
            </div>
            <h3 class="fw-bold mb-0 text-success"><?= $stats['in_stock'] ?></h3>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <div class="d-flex align-items-center mb-2">
                <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-2 me-2">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <span class="text-muted small fw-medium">Sắp hết</span>
            </div>
            <h3 class="fw-bold mb-0 text-warning"><?= $stats['low_stock'] ?></h3>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
            <div class="d-flex align-items-center mb-2">
                <div class="bg-danger bg-opacity-10 text-danger rounded-circle p-2 me-2">
                    <i class="fa-solid fa-circle-xmark"></i>
                </div>
                <span class="text-muted small fw-medium">Hết hàng</span>
            </div>
            <h3 class="fw-bold mb-0 text-danger"><?= $stats['out_of_stock'] ?></h3>
        </div>
    </div>
</div>

<!-- Table -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-4 py-3 fw-medium border-0">STT</th>
                        <th class="py-3 fw-medium border-0">Nguyên liệu</th>
                        <th class="py-3 fw-medium border-0">Đơn vị</th>
                        <th class="py-3 fw-medium border-0 text-end">Số lượng tồn</th>
                        <th class="py-3 fw-medium border-0 text-end">Mức tối thiểu</th>
                        <th class="py-3 fw-medium border-0 text-end">Giá nhập</th>
                        <th class="py-3 fw-medium border-0 text-center">Trạng thái</th>
                        <th class="pe-4 py-3 fw-medium border-0 text-end">Hành động</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <?php if (empty($ingredients)): ?>
                        <tr><td colspan="8" class="text-center py-5 text-muted">Kho trống. Chưa có nguyên liệu nào.</td></tr>
                    <?php else: ?>
                        <?php 
                        $stt = 1;
                        foreach ($ingredients as $item): 
                            $qty = (float)$item['quantity'];
                            $min = (float)$item['min_quantity'];
                            $isOut = ($qty <= 0);
                            $isLow = ($qty <= $min && !$isOut);
                        ?>
                        <tr>
                            <td class="ps-4 text-muted"><?= str_pad($stt++, 2, '0', STR_PAD_LEFT) ?></td>
                            <td><div class="fw-bold text-dark"><?= e($item['ingredient_name']) ?></div></td>
                            <td><span class="badge bg-light text-dark border px-2 py-1"><?= e($item['unit']) ?></span></td>
                            <td class="text-end fw-bold font-monospace <?= $isOut ? 'text-danger' : ($isLow ? 'text-warning' : 'text-forest') ?>">
                                <?= number_format($qty, 2) ?>
                            </td>
                            <td class="text-end text-muted font-monospace"><?= number_format($min, 2) ?></td>
                            <td class="text-end fw-medium"><?= money($item['price']) ?></td>
                            <td class="text-center">
                                <?php if ($isOut): ?>
                                    <span class="badge bg-danger rounded-pill px-3">Hết hàng</span>
                                <?php elseif ($isLow): ?>
                                    <span class="badge bg-warning text-dark rounded-pill px-3">Sắp hết</span>
                                <?php else: ?>
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Còn hàng</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <button class="btn btn-sm btn-outline-secondary" title="Sửa" data-bs-toggle="modal" data-bs-target="#editModal<?= (int)$item['id'] ?>">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <form action="<?= e(admin_url('inventory', ['action' => 'delete'])) ?>" method="POST" class="m-0" onsubmit="return confirm('Bạn có chắc muốn xóa nguyên liệu này?');">
                                        <input type="hidden" name="id" value="<?= (int)$item['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal Sửa Nguyên Liệu -->
                        <div class="modal fade" id="editModal<?= (int)$item['id'] ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 rounded-4 shadow">
                                    <form action="<?= e(admin_url('inventory', ['action' => 'update'])) ?>" method="POST">
                                        <input type="hidden" name="id" value="<?= (int)$item['id'] ?>">
                                        <div class="modal-header border-bottom-0 pb-0 px-4 pt-4">
                                            <h5 class="modal-title font-serif fw-bold text-forest">Sửa Nguyên Liệu</h5>
                                            <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body px-4 py-3">
                                            <div class="mb-3">
                                                <label class="form-label text-muted small fw-medium mb-1">Tên nguyên liệu <span class="text-danger">*</span></label>
                                                <input type="text" name="name" class="form-control border-secondary border-opacity-25 shadow-none" value="<?= e($item['ingredient_name']) ?>" required>
                                            </div>
                                            <div class="row g-3">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label text-muted small fw-medium mb-1">Đơn vị tính <span class="text-danger">*</span></label>
                                                    <input type="text" name="unit" class="form-control border-secondary border-opacity-25 shadow-none" value="<?= e($item['unit']) ?>" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label text-muted small fw-medium mb-1">Mức tồn tối thiểu</label>
                                                    <input type="number" step="0.01" name="min_quantity" class="form-control border-secondary border-opacity-25 shadow-none" value="<?= (float)$item['min_quantity'] ?>" required>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label text-muted small fw-medium mb-1">Giá nhập (VNĐ)</label>
                                                <input type="number" step="100" name="price" class="form-control border-secondary border-opacity-25 shadow-none" value="<?= (float)$item['price'] ?>" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-top-0 pt-0 px-4 pb-4">
                                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                                            <button type="submit" class="btn btn-forest rounded-pill px-4">Lưu thay đổi</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Thêm Nguyên Liệu -->
<div class="modal fade" id="addIngredientModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <form action="<?= e(admin_url('inventory', ['action' => 'store'])) ?>" method="POST">
                <div class="modal-header border-bottom-0 pb-0 px-4 pt-4">
                    <h5 class="modal-title font-serif fw-bold text-forest">Thêm Nguyên Liệu Mới</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4 py-3">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-medium mb-1">Tên nguyên liệu <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control border-secondary border-opacity-25 shadow-none" placeholder="VD: Trà Ô Long, Sữa tươi..." required>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small fw-medium mb-1">Đơn vị tính <span class="text-danger">*</span></label>
                            <input type="text" name="unit" class="form-control border-secondary border-opacity-25 shadow-none" placeholder="VD: kg, gam, ml, hộp..." required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small fw-medium mb-1">Tồn kho ban đầu</label>
                            <input type="number" step="0.01" name="quantity" class="form-control border-secondary border-opacity-25 shadow-none" value="0">
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small fw-medium mb-1">Mức tồn tối thiểu</label>
                            <input type="number" step="0.01" name="min_quantity" class="form-control border-secondary border-opacity-25 shadow-none" value="5" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small fw-medium mb-1">Giá nhập (VNĐ)</label>
                            <input type="number" step="100" name="price" class="form-control border-secondary border-opacity-25 shadow-none" value="0" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-forest rounded-pill px-4">Lưu nguyên liệu</button>
                </div>
            </form>
        </div>
    </div>
</div>

