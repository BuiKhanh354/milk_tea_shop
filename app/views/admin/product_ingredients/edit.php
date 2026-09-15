<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="font-serif fw-bold text-dark mb-0">Cấu hình Công thức</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="admin.php?route=product_ingredients" class="text-muted text-decoration-none">Công thức SP</a></li>
                <li class="breadcrumb-item active"><?= htmlspecialchars($product['name']) ?></li>
            </ol>
        </nav>
    </div>
    <a href="admin.php?route=product_ingredients" class="btn btn-light border">
        <i class="fa-solid fa-arrow-left me-2"></i> Quay lại
    </a>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 text-center">
            <div class="card-body p-4">
                <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="rounded-circle object-fit-cover mb-3" width="120" height="120" onerror="this.src='https://via.placeholder.com/120?text=SP'">
                <h5 class="fw-bold text-dark mb-1"><?= htmlspecialchars($product['name']) ?></h5>
                <span class="badge bg-light text-dark border px-2 py-1 mb-3"><?= htmlspecialchars($product['category_name']) ?></span>
                <p class="text-muted small fst-italic">Thiết lập chính xác định lượng nguyên liệu để hệ thống tự động trừ kho khi bán hàng.</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <form action="admin.php?route=product_ingredients&action=store" method="POST" id="recipeForm">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0">Danh sách Nguyên liệu</h6>
                        <button type="button" class="btn btn-sm btn-outline-forest rounded-pill px-3" onclick="addIngredientRow()">
                            <i class="fa-solid fa-plus me-1"></i> Thêm dòng
                        </button>
                    </div>
                    
                    <div id="ingredientsList">
                        <?php if(empty($recipe)): ?>
                            <!-- Mặc định 1 dòng trống -->
                            <div class="row g-2 mb-3 ingredient-row align-items-end">
                                <div class="col-6">
                                    <label class="form-label text-muted small fw-medium">Nguyên liệu</label>
                                    <select name="ingredient_id[]" class="form-select shadow-none ing-select" required onchange="updateUnitLabel(this)">
                                        <option value="">-- Chọn nguyên liệu --</option>
                                        <?php foreach($all_ingredients as $ing): ?>
                                            <option value="<?= $ing['id'] ?>" data-unit="<?= htmlspecialchars($ing['unit']) ?>"><?= htmlspecialchars($ing['ingredient_name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-5">
                                    <label class="form-label text-muted small fw-medium">Định lượng (cho 1 phần)</label>
                                    <div class="input-group">
                                        <input type="number" name="quantity[]" class="form-control shadow-none" required min="0.01" step="0.01" placeholder="0.00">
                                        <span class="input-group-text bg-light text-muted unit-label">--</span>
                                    </div>
                                </div>
                                <div class="col-1 text-end">
                                    <button type="button" class="btn btn-light text-danger border w-100" onclick="removeRow(this)"><i class="fa-solid fa-trash"></i></button>
                                </div>
                            </div>
                        <?php else: ?>
                            <?php foreach($recipe as $r): ?>
                                <div class="row g-2 mb-3 ingredient-row align-items-end">
                                    <div class="col-6">
                                        <label class="form-label text-muted small fw-medium">Nguyên liệu</label>
                                        <select name="ingredient_id[]" class="form-select shadow-none ing-select" required onchange="updateUnitLabel(this)">
                                            <option value="">-- Chọn nguyên liệu --</option>
                                            <?php foreach($all_ingredients as $ing): ?>
                                                <option value="<?= $ing['id'] ?>" data-unit="<?= htmlspecialchars($ing['unit']) ?>" <?= $ing['id'] == $r['inventory_id'] ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($ing['ingredient_name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-5">
                                        <label class="form-label text-muted small fw-medium">Định lượng (cho 1 phần)</label>
                                        <div class="input-group">
                                            <input type="number" name="quantity[]" class="form-control shadow-none" required min="0.01" step="0.01" value="<?= floatval($r['quantity']) ?>">
                                            <span class="input-group-text bg-light text-muted unit-label"><?= htmlspecialchars($r['unit']) ?></span>
                                        </div>
                                    </div>
                                    <div class="col-1 text-end">
                                        <button type="button" class="btn btn-light text-danger border w-100" onclick="removeRow(this)"><i class="fa-solid fa-trash"></i></button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <hr class="my-4" style="border-color: rgba(0,0,0,0.05);">

                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-forest px-4">Lưu công thức</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function updateUnitLabel(selectElement) {
    const row = selectElement.closest('.ingredient-row');
    const unitLabel = row.querySelector('.unit-label');
    
    if (selectElement.selectedIndex > 0) {
        const option = selectElement.options[selectElement.selectedIndex];
        unitLabel.textContent = option.getAttribute('data-unit');
    } else {
        unitLabel.textContent = '--';
    }
}

function removeRow(btn) {
    const row = btn.closest('.ingredient-row');
    const list = document.getElementById('ingredientsList');
    if (list.querySelectorAll('.ingredient-row').length > 1) {
        row.remove();
    } else {
        // Clear value if last row
        row.querySelector('select').value = '';
        row.querySelector('input').value = '';
        row.querySelector('.unit-label').textContent = '--';
    }
}

function addIngredientRow() {
    const list = document.getElementById('ingredientsList');
    const firstRow = list.querySelector('.ingredient-row');
    const newRow = firstRow.cloneNode(true);
    
    // Clear values
    newRow.querySelector('select').value = '';
    newRow.querySelector('input').value = '';
    newRow.querySelector('.unit-label').textContent = '--';
    
    list.appendChild(newRow);
}
</script>
