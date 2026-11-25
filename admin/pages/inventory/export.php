<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="font-serif fw-bold text-dark mb-0 text-danger">Xuất kho nguyên liệu</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="<?= e(admin_url('inventory')) ?>" class="text-muted text-decoration-none">Kho</a></li>
                <li class="breadcrumb-item active text-danger">Xuất kho</li>
            </ol>
        </nav>
    </div>
    <a href="<?= e(admin_url('inventory')) ?>" class="btn btn-light rounded-pill border">
        <i class="fa-solid fa-arrow-left me-2"></i> Quay lại
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4 border-top border-danger border-3">
    <div class="card-body p-4">
        <form action="<?= e(admin_url('inventory', ['action' => 'storeExport'])) ?>" method="POST" id="exportForm">
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label text-muted small fw-medium mb-1">Chọn Nguyên liệu cần xuất <span class="text-danger">*</span></label>
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
                    <label class="form-label text-muted small fw-medium mb-1">Số lượng xuất <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" name="quantity" id="quantity" class="form-control border-secondary border-opacity-25 shadow-none" required min="0.01" step="0.01" placeholder="Nhập số lượng...">
                        <span class="input-group-text bg-light text-danger fw-bold border-secondary border-opacity-25" id="unit_display">--</span>
                    </div>
                    <div class="form-text small text-danger" id="qty_error" style="display:none;">Số lượng xuất không được vượt quá số lượng tồn!</div>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label text-muted small fw-medium mb-1">Lý do xuất <span class="text-danger">*</span></label>
                    <select name="reason" class="form-select border-secondary border-opacity-25 shadow-none" required>
                        <option value="">-- Chọn lý do --</option>
                        <option value="Sử dụng trong pha chế">Sử dụng trong pha chế</option>
                        <option value="Hàng hỏng/Hết hạn">Hàng hỏng / Hết hạn</option>
                        <option value="Chuyển kho khác">Chuyển sang chi nhánh khác</option>
                        <option value="Lý do khác">Lý do khác (Ghi chú rõ bên dưới)</option>
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label text-muted small fw-medium mb-1">Ghi chú thêm</label>
                    <textarea name="note" class="form-control border-secondary border-opacity-25 shadow-none" rows="3" placeholder="Ghi chú thêm về lý do xuất kho (nếu có)"></textarea>
                </div>
            </div>

            <hr class="my-4">

            <div class="d-flex justify-content-end gap-2">
                <a href="<?= e(admin_url('inventory')) ?>" class="btn btn-light rounded-pill px-4">Hủy</a>
                <button type="submit" class="btn btn-danger rounded-pill px-4" id="btnSubmit"><i class="fa-solid fa-upload me-2"></i> Xác nhận Xuất Kho</button>
            </div>
        </form>
    </div>
</div>

