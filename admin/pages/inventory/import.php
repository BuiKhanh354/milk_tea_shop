<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="font-serif fw-bold text-dark mb-0">Nhập kho nguyên liệu</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="<?= e(admin_url('inventory')) ?>" class="text-muted text-decoration-none">Kho</a></li>
                <li class="breadcrumb-item active text-forest">Nhập kho</li>
            </ol>
        </nav>
    </div>
    <a href="<?= e(admin_url('inventory')) ?>" class="btn btn-light rounded-pill border">
        <i class="fa-solid fa-arrow-left me-2"></i> Quay lại
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <form action="<?= e(admin_url('inventory', ['action' => 'storeImport'])) ?>" method="POST">
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label text-muted small fw-medium mb-1">Chọn Nguyên liệu cần nhập <span class="text-danger">*</span></label>
                    <select name="inventory_id" id="inventory_id" class="form-select border-secondary border-opacity-25 shadow-none" required onchange="updateUnit()">
                        <option value="">-- Chọn nguyên liệu --</option>
                        <?php foreach($ingredients as $item): ?>
                            <option value="<?= (int)$item['id'] ?>" data-unit="<?= e($item['unit']) ?>" data-qty="<?= (float)$item['quantity'] ?>">
                                <?= e($item['ingredient_name']) ?> (Tồn hiện tại: <?= (float)$item['quantity'] ?> <?= e($item['unit']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label text-muted small fw-medium mb-1">Số lượng nhập thêm <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" name="quantity" class="form-control border-secondary border-opacity-25 shadow-none" required min="0.01" step="0.01" placeholder="Nhập số lượng...">
                        <span class="input-group-text bg-light text-muted fw-bold border-secondary border-opacity-25" id="unit_display">--</span>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label text-muted small fw-medium mb-1">Ghi chú nhập kho</label>
                    <textarea name="note" class="form-control border-secondary border-opacity-25 shadow-none" rows="3" placeholder="Ghi chú về lô hàng này (Tên nhà cung cấp, ngày hết hạn...)"></textarea>
                </div>
            </div>

            <hr class="my-4">

            <div class="d-flex justify-content-end gap-2">
                <a href="<?= e(admin_url('inventory')) ?>" class="btn btn-light rounded-pill px-4">Hủy</a>
                <button type="submit" class="btn btn-forest rounded-pill px-4"><i class="fa-solid fa-download me-2"></i> Xác nhận Nhập Kho</button>
            </div>
        </form>
    </div>
</div>

