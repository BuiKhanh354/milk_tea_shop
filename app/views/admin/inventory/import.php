<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="font-serif fw-bold text-dark mb-0">Nhập kho</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="admin.php?route=inventory" class="text-muted text-decoration-none">Kho</a></li>
                <li class="breadcrumb-item active">Nhập kho</li>
            </ol>
        </nav>
    </div>
    <a href="admin.php?route=inventory" class="btn btn-light border">
        <i class="fa-solid fa-arrow-left me-2"></i> Quay lại
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <form action="admin.php?route=inventory&action=storeImport" method="POST">
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label text-muted small fw-medium">Chọn Nguyên liệu cần nhập <span class="text-danger">*</span></label>
                    <select name="inventory_id" id="inventory_id" class="form-select shadow-none" required onchange="updateUnit()">
                        <option value="">-- Chọn nguyên liệu --</option>
                        <?php foreach($ingredients as $item): ?>
                            <option value="<?= $item['id'] ?>" data-unit="<?= htmlspecialchars($item['unit']) ?>" data-qty="<?= floatval($item['quantity']) ?>">
                                <?= htmlspecialchars($item['ingredient_name']) ?> (Tồn: <?= floatval($item['quantity']) ?> <?= htmlspecialchars($item['unit']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label text-muted small fw-medium">Số lượng nhập thêm <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" name="quantity" class="form-control shadow-none" required min="0.01" step="0.01" placeholder="Nhập số lượng...">
                        <span class="input-group-text bg-light text-muted fw-bold" id="unit_display">--</span>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label text-muted small fw-medium">Ghi chú nhập kho</label>
                    <textarea name="note" class="form-control shadow-none" rows="3" placeholder="Ghi chú về lô hàng này (Tên nhà cung cấp, ngày hết hạn...)"></textarea>
                </div>
            </div>

            <hr class="my-4" style="border-color: rgba(0,0,0,0.05);">

            <div class="d-flex justify-content-end gap-2">
                <a href="admin.php?route=inventory" class="btn btn-light px-4">Hủy</a>
                <button type="submit" class="btn btn-forest px-4"><i class="fa-solid fa-download me-2"></i> Xác nhận Nhập Kho</button>
            </div>
        </form>
    </div>
</div>

<script>
function updateUnit() {
    const select = document.getElementById('inventory_id');
    const display = document.getElementById('unit_display');
    
    if (select.selectedIndex > 0) {
        const option = select.options[select.selectedIndex];
        display.textContent = option.getAttribute('data-unit');
    } else {
        display.textContent = '--';
    }
}
</script>
