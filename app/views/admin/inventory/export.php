<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="font-serif fw-bold text-dark mb-0 text-danger">Xuất kho</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="admin.php?route=inventory" class="text-muted text-decoration-none">Kho</a></li>
                <li class="breadcrumb-item active">Xuất kho</li>
            </ol>
        </nav>
    </div>
    <a href="admin.php?route=inventory" class="btn btn-light border">
        <i class="fa-solid fa-arrow-left me-2"></i> Quay lại
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4 border-top border-danger border-3">
    <div class="card-body p-4">
        <form action="admin.php?route=inventory&action=storeExport" method="POST" id="exportForm">
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label text-muted small fw-medium">Chọn Nguyên liệu cần xuất <span class="text-danger">*</span></label>
                    <select name="inventory_id" id="inventory_id" class="form-select shadow-none border-danger border-opacity-25" required onchange="updateUnit()">
                        <option value="">-- Chọn nguyên liệu --</option>
                        <?php foreach($ingredients as $item): ?>
                            <option value="<?= $item['id'] ?>" data-unit="<?= htmlspecialchars($item['unit']) ?>" data-qty="<?= floatval($item['quantity']) ?>">
                                <?= htmlspecialchars($item['ingredient_name']) ?> (Tồn: <?= floatval($item['quantity']) ?> <?= htmlspecialchars($item['unit']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label text-muted small fw-medium">Số lượng xuất <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" name="quantity" id="quantity" class="form-control shadow-none border-danger border-opacity-25" required min="0.01" step="0.01" placeholder="Nhập số lượng...">
                        <span class="input-group-text bg-light text-danger fw-bold" id="unit_display">--</span>
                    </div>
                    <div class="form-text small text-danger" id="qty_error" style="display: none;">Số lượng xuất không được vượt quá số lượng tồn!</div>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label text-muted small fw-medium">Lý do xuất <span class="text-danger">*</span></label>
                    <select name="reason" class="form-select shadow-none border-danger border-opacity-25" required>
                        <option value="">-- Chọn lý do --</option>
                        <option value="Sử dụng trong pha chế">Sử dụng trong pha chế</option>
                        <option value="Hàng hỏng/Hết hạn">Hàng hỏng / Hết hạn</option>
                        <option value="Chuyển kho khác">Chuyển sang chi nhánh khác</option>
                        <option value="Lý do khác">Lý do khác (Ghi chú rõ bên dưới)</option>
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label text-muted small fw-medium">Ghi chú thêm</label>
                    <textarea name="note" class="form-control shadow-none" rows="3" placeholder="Ghi chú thêm về lý do xuất kho (nếu có)"></textarea>
                </div>
            </div>

            <hr class="my-4" style="border-color: rgba(0,0,0,0.05);">

            <div class="d-flex justify-content-end gap-2">
                <a href="admin.php?route=inventory" class="btn btn-light px-4">Hủy</a>
                <button type="submit" class="btn btn-danger px-4" id="btnSubmit"><i class="fa-solid fa-upload me-2"></i> Xác nhận Xuất Kho</button>
            </div>
        </form>
    </div>
</div>

<script>
let currentMaxQty = 0;

function updateUnit() {
    const select = document.getElementById('inventory_id');
    const display = document.getElementById('unit_display');
    const qtyInput = document.getElementById('quantity');
    
    if (select.selectedIndex > 0) {
        const option = select.options[select.selectedIndex];
        display.textContent = option.getAttribute('data-unit');
        currentMaxQty = parseFloat(option.getAttribute('data-qty'));
        validateQuantity();
    } else {
        display.textContent = '--';
        currentMaxQty = 0;
    }
}

function validateQuantity() {
    const qtyInput = document.getElementById('quantity');
    const errorMsg = document.getElementById('qty_error');
    const btnSubmit = document.getElementById('btnSubmit');
    const val = parseFloat(qtyInput.value || 0);

    if (val > currentMaxQty) {
        errorMsg.style.display = 'block';
        qtyInput.classList.add('is-invalid');
        btnSubmit.disabled = true;
    } else {
        errorMsg.style.display = 'none';
        qtyInput.classList.remove('is-invalid');
        btnSubmit.disabled = false;
    }
}

document.getElementById('quantity').addEventListener('input', validateQuantity);
document.getElementById('exportForm').addEventListener('submit', function(e) {
    validateQuantity();
    if (document.getElementById('btnSubmit').disabled) {
        e.preventDefault();
    }
});
</script>
