<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="font-serif fw-bold text-dark mb-0">Cấu hình Công thức theo Size</h4>
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

<style>
    #sizeTabs .nav-link {
        color: #212529;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        transition: all 0.2s ease-in-out;
    }
    #sizeTabs .nav-link:hover {
        background-color: #e9ecef;
        color: #000;
        border-color: #ced4da;
    }
    #sizeTabs .nav-link.active {
        background-color: #1a1a1a !important; /* Màu đen sang trọng */
        color: #ffffff !important;
        border-color: #1a1a1a !important;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.01);
    }
    #sizeTabs .nav-link.active .badge {
        background-color: #ffffff !important;
        color: #1a1a1a !important;
    }
    #sizeTabs .nav-link:not(.active) .badge {
        background-color: #e2e8f0 !important;
        color: #475569 !important;
    }
</style>

<div class="row g-4">
    <!-- Cột trái: Thông tin sản phẩm & Gợi ý định lượng -->
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 text-center mb-4">
            <div class="card-body p-4">
                <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="rounded-circle object-fit-cover mb-3" width="110" height="110" onerror="this.src='https://via.placeholder.com/110?text=SP'">
                <h5 class="fw-bold text-dark mb-1"><?= htmlspecialchars($product['name']) ?></h5>
                <span class="badge bg-light text-dark border px-2 py-1 mb-3"><?= htmlspecialchars($product['category_name']) ?></span>
                <p class="text-muted small mb-0">Thiết lập định lượng nguyên liệu cho từng kích cỡ (Size) để hệ thống tự động trừ kho chính xác khi bán hàng.</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 bg-light border-start border-4 border-forest">
            <div class="card-body p-3">
                <h6 class="fw-bold text-dark mb-2"><i class="fa-solid text-warning me-1"></i> Gợi ý đơn vị định lượng:</h6>
                <ul class="small text-muted mb-0 ps-3">
                    <li class="mb-1"><strong>Đường / Siro:</strong> ml hoặc gam (ví dụ: 25ml - 40ml).</li>
                    <li class="mb-1"><strong>Sữa tươi / Sữa đặc:</strong> ml hoặc lít.</li>
                    <li class="mb-1"><strong>Trà cốt / Cà phê:</strong> ml hoặc g trà khô.</li>
                    <li><strong>Topping kèm theo:</strong> gam (ví dụ: 40g - 60g trân châu).</li>
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Cột phải: Form công thức theo từng Size -->
    <div class="col-md-9">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <form action="admin.php?route=product_ingredients&action=store" method="POST" id="recipeForm">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">

                    <!-- Danh sách các Tab Size -->
                    <ul class="nav nav-pills mb-4 gap-2 border-bottom pb-3" id="sizeTabs" role="tablist">
                        <!-- Tab áp dụng chung -->
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill active px-4 fw-medium" id="tab-btn-0" data-bs-toggle="pill" data-bs-target="#tab-size-0" type="button" role="tab">
                                <i class="fa-solid fa-layer-group me-1"></i> Mặc định (Chung)
                                <span class="badge bg-white text-dark ms-1 rounded-pill" id="badge-count-0"><?= count($groupedRecipes[0] ?? []) ?></span>
                            </button>
                        </li>
                        <!-- Tabs theo từng Size (S, M, L, ...) -->
                        <?php foreach ($sizes as $s): ?>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill px-4 fw-medium" id="tab-btn-<?= $s['id'] ?>" data-bs-toggle="pill" data-bs-target="#tab-size-<?= $s['id'] ?>" type="button" role="tab">
                                <i class="fa-solid fa-mug-hot me-1"></i> Size <?= htmlspecialchars($s['name']) ?>
                                <span class="badge bg-white text-dark ms-1 rounded-pill" id="badge-count-<?= $s['id'] ?>"><?= count($groupedRecipes[$s['id']] ?? []) ?></span>
                            </button>
                        </li>
                        <?php endforeach; ?>
                    </ul>

                    <!-- Nội dung các Tab -->
                    <div class="tab-content" id="sizeTabsContent">
                        
                        <!-- TAB SIZE 0: MẶC ĐỊNH CHUNG -->
                        <?php 
                        $tabSizes = array_merge([['id' => 0, 'name' => 'Mặc định (Chung)']], $sizes);
                        foreach ($tabSizes as $idx => $sObj): 
                            $sid = $sObj['id'];
                            $sName = $sObj['name'];
                            $currentList = $groupedRecipes[$sid] ?? [];
                            $isActive = ($idx === 0) ? 'show active' : '';
                        ?>
                        <div class="tab-pane fade <?= $isActive ?>" id="tab-size-<?= $sid ?>" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3 bg-light p-3 rounded-3 border">
                                <div>
                                    <span class="fw-bold text-dark">Công thức: <?= htmlspecialchars($sName) ?></span>
                                    <span class="text-muted small d-block">Định lượng các nguyên liệu (trà, sữa, đường, topping...) dùng cho 1 phần <?= htmlspecialchars($sName) ?></span>
                                </div>
                                <div class="d-flex gap-2">
                                    <?php if ($sid != 0): ?>
                                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="copyFromDefault(<?= $sid ?>)">
                                        <i class="fa-regular fa-copy me-1"></i> Sao chép từ Mặc định
                                    </button>
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-sm btn-forest rounded-pill px-3" onclick="addIngredientRow(<?= $sid ?>)">
                                        <i class="fa-solid fa-plus me-1"></i> Thêm nguyên liệu
                                    </button>
                                </div>
                            </div>

                            <div class="ingredients-list" id="list-size-<?= $sid ?>">
                                <?php if (empty($currentList)): ?>
                                    <div class="row g-2 mb-3 ingredient-row align-items-end">
                                        <div class="col-6">
                                            <label class="form-label text-muted small fw-medium">Nguyên liệu kho</label>
                                            <select name="recipes[<?= $sid ?>][ingredient_id][]" class="form-select shadow-none ing-select" onchange="updateUnitLabel(this)">
                                                <option value="">-- Chọn nguyên liệu (Đường, Sữa, Trà, Topping...) --</option>
                                                <?php foreach($all_ingredients as $ing): ?>
                                                    <option value="<?= $ing['id'] ?>" data-unit="<?= htmlspecialchars($ing['unit']) ?>">
                                                        <?= htmlspecialchars($ing['ingredient_name']) ?> (Đơn vị kho: <?= htmlspecialchars($ing['unit']) ?>)
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-5">
                                            <label class="form-label text-muted small fw-medium">Định lượng tiêu hao</label>
                                            <div class="input-group">
                                                <input type="number" name="recipes[<?= $sid ?>][quantity][]" class="form-control shadow-none" min="0" step="any" placeholder="Ví dụ: 30 (ml) hoặc 0.03 (kg)">
                                                <span class="input-group-text bg-light text-muted unit-label">--</span>
                                            </div>
                                        </div>
                                        <div class="col-1 text-end">
                                            <button type="button" class="btn btn-light text-danger border w-100" onclick="removeRow(this, <?= $sid ?>)" title="Xóa dòng"><i class="fa-solid fa-trash"></i></button>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($currentList as $r): ?>
                                    <div class="row g-2 mb-3 ingredient-row align-items-end">
                                        <div class="col-6">
                                            <label class="form-label text-muted small fw-medium">Nguyên liệu kho</label>
                                            <select name="recipes[<?= $sid ?>][ingredient_id][]" class="form-select shadow-none ing-select" onchange="updateUnitLabel(this)">
                                                <option value="">-- Chọn nguyên liệu (Đường, Sữa, Trà, Topping...) --</option>
                                                <?php foreach($all_ingredients as $ing): ?>
                                                    <option value="<?= $ing['id'] ?>" data-unit="<?= htmlspecialchars($ing['unit']) ?>" <?= $ing['id'] == $r['inventory_id'] ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($ing['ingredient_name']) ?> (Đơn vị kho: <?= htmlspecialchars($ing['unit']) ?>)
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-5">
                                            <label class="form-label text-muted small fw-medium">Định lượng tiêu hao</label>
                                            <div class="input-group">
                                                <input type="number" name="recipes[<?= $sid ?>][quantity][]" class="form-control shadow-none" min="0" step="any" value="<?= floatval($r['quantity']) ?>">
                                                <span class="input-group-text bg-light text-muted unit-label"><?= htmlspecialchars($r['unit']) ?></span>
                                            </div>
                                        </div>
                                        <div class="col-1 text-end">
                                            <button type="button" class="btn btn-light text-danger border w-100" onclick="removeRow(this, <?= $sid ?>)" title="Xóa dòng"><i class="fa-solid fa-trash"></i></button>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>

                            <!-- BẢNG QUY CHUẨN MỨC ĐƯỜNG THEO % -->
                            <div class="mt-4 pt-3 border-top">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0">
                                            <i class="fa-solid me-1"></i> Quy chuẩn Định lượng Mức Đường (% đường)
                                        </h6>
                                        <span class="text-muted small">Quy định số lượng (ml / gam) đường thực tế tương ứng với từng mức % mà khách chọn</span>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-warning text-dark rounded-pill px-3" onclick="autoCalculateSugar(<?= $sid ?>)">
                                        <i class="fa-solid me-1"></i> Tự tính nhanh từ mức 100%
                                    </button>
                                </div>

                                <div class="p-3 bg-light rounded-3 border mb-3">
                                    <div class="row g-3 align-items-center mb-3">
                                        <div class="col-md-5">
                                            <label class="form-label text-muted small fw-medium mb-1">Loại đường trong kho</label>
                                            <select name="sugar_levels[<?= $sid ?>][inventory_id]" class="form-select shadow-none sugar-ing-select" onchange="updateSugarUnitLabel(this, <?= $sid ?>)">
                                                <option value="">-- Chọn loại đường (Đường nước, Đường cát...) --</option>
                                                <?php foreach ($all_ingredients as $ing): 
                                                    $isSugarSelected = (($groupedSugarLevels[$sid]['inventory_id'] ?? 0) == $ing['id']) || (stripos($ing['ingredient_name'], 'đường') !== false && empty($groupedSugarLevels[$sid]['inventory_id']));
                                                ?>
                                                    <option value="<?= $ing['id'] ?>" data-unit="<?= htmlspecialchars($ing['unit']) ?>" <?= $isSugarSelected ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($ing['ingredient_name']) ?> (Đơn vị: <?= htmlspecialchars($ing['unit']) ?>)
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-7">
                                            <span class="text-muted small d-block">
                                                <i class="fa-solid fa-circle-info me-1"></i> Khi khách order ly chọn % đường, hệ thống sẽ trừ đúng số lượng đường này trong kho.
                                            </span>
                                        </div>
                                    </div>

                                    <div class="row g-2">
                                        <?php 
                                        $levelsMap = $groupedSugarLevels[$sid]['levels'] ?? [];
                                        foreach ($sugarOptions as $sOpt): 
                                            $val = $sOpt['value'];
                                            $currentQty = isset($levelsMap[$val]) ? $levelsMap[$val] : ($val === 0 ? 0 : '');
                                        ?>
                                        <div class="col-6 col-md">
                                            <div class="bg-white p-2 rounded border text-center">
                                                <div class="fw-bold text-dark small mb-1"><?= $val ?>% Đường</div>
                                                <div class="input-group input-group-sm">
                                                    <input type="number" 
                                                           name="sugar_levels[<?= $sid ?>][levels][<?= $val ?>]" 
                                                           class="form-control text-center shadow-none sugar-input-<?= $sid ?>" 
                                                           data-percent="<?= $val ?>" 
                                                           step="any" 
                                                           min="0" 
                                                           value="<?= $currentQty ?>" 
                                                           placeholder="0">
                                                    <span class="input-group-text bg-light sugar-unit-<?= $sid ?> small">--</span>
                                                </div>
                                                <span class="text-muted text-nowrap mt-1 d-block" style="font-size: 11px;"><?= htmlspecialchars($sOpt['name']) ?></span>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>

                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">
                            <i class="fa-solid fa-circle-check text-success me-1"></i> Định lượng sẽ được tự động nhân và trừ kho khi đơn hàng hoàn tất.
                        </span>
                        <div class="d-flex gap-2">
                            <a href="admin.php?route=product_ingredients" class="btn btn-light border px-4">Hủy</a>
                            <button type="submit" class="btn btn-forest px-5 fw-bold">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Lưu toàn bộ công thức
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Template HTML cho dòng nguyên liệu mới -->
<template id="ingredientRowTemplate">
    <div class="row g-2 mb-3 ingredient-row align-items-end">
        <div class="col-6">
            <label class="form-label text-muted small fw-medium">Nguyên liệu kho</label>
            <select name="" class="form-select shadow-none ing-select" onchange="updateUnitLabel(this)">
                <option value="">-- Chọn nguyên liệu (Đường, Sữa, Trà, Topping...) --</option>
                <?php foreach($all_ingredients as $ing): ?>
                    <option value="<?= $ing['id'] ?>" data-unit="<?= htmlspecialchars($ing['unit']) ?>">
                        <?= htmlspecialchars($ing['ingredient_name']) ?> (Đơn vị kho: <?= htmlspecialchars($ing['unit']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-5">
            <label class="form-label text-muted small fw-medium">Định lượng tiêu hao</label>
            <div class="input-group">
                <input type="number" name="" class="form-control shadow-none" min="0" step="any" placeholder="Ví dụ: 30 (ml) hoặc 0.03 (kg)">
                <span class="input-group-text bg-light text-muted unit-label">--</span>
            </div>
        </div>
        <div class="col-1 text-end">
            <button type="button" class="btn btn-light text-danger border w-100" onclick="" title="Xóa dòng"><i class="fa-solid fa-trash"></i></button>
        </div>
    </div>
</template>

<script>
function updateUnitLabel(selectElement) {
    const row = selectElement.closest('.ingredient-row');
    const unitLabel = row.querySelector('.unit-label');
    
    if (selectElement.selectedIndex > 0) {
        const option = selectElement.options[selectElement.selectedIndex];
        unitLabel.textContent = option.getAttribute('data-unit') || '--';
    } else {
        unitLabel.textContent = '--';
    }
}

function removeRow(btn, sizeId) {
    const row = btn.closest('.ingredient-row');
    const container = document.getElementById('list-size-' + sizeId);
    if (container.querySelectorAll('.ingredient-row').length > 1) {
        row.remove();
    } else {
        // Nếu là dòng cuối cùng thì chỉ xóa dữ liệu
        row.querySelector('select').value = '';
        row.querySelector('input').value = '';
        row.querySelector('.unit-label').textContent = '--';
    }
    updateBadgeCount(sizeId);
}

function addIngredientRow(sizeId) {
    const container = document.getElementById('list-size-' + sizeId);
    const template = document.getElementById('ingredientRowTemplate');
    const clone = template.content.cloneNode(true);
    
    const row = clone.querySelector('.ingredient-row');
    const select = row.querySelector('select');
    const input = row.querySelector('input');
    const deleteBtn = row.querySelector('button');

    select.name = `recipes[${sizeId}][ingredient_id][]`;
    input.name = `recipes[${sizeId}][quantity][]`;
    deleteBtn.setAttribute('onclick', `removeRow(this, ${sizeId})`);

    container.appendChild(row);
    updateBadgeCount(sizeId);
}

function updateBadgeCount(sizeId) {
    const container = document.getElementById('list-size-' + sizeId);
    let count = 0;
    container.querySelectorAll('.ingredient-row').forEach(r => {
        const sel = r.querySelector('select');
        const inp = r.querySelector('input');
        if (sel && sel.value && inp && parseFloat(inp.value) > 0) {
            count++;
        }
    });
    const badge = document.getElementById('badge-count-' + sizeId);
    if (badge) badge.textContent = count;
}

function copyFromDefault(targetSizeId) {
    if (!confirm('Bạn có muốn sao chép công thức từ phần "Mặc định (Chung)" sang Size này không? (Công thức hiện tại của Size này sẽ được thay thế)')) {
        return;
    }

    const defaultContainer = document.getElementById('list-size-0');
    const targetContainer = document.getElementById('list-size-' + targetSizeId);
    targetContainer.innerHTML = '';

    const defaultRows = defaultContainer.querySelectorAll('.ingredient-row');
    defaultRows.forEach(r => {
        const selVal = r.querySelector('select').value;
        const qtyVal = r.querySelector('input').value;
        const unitVal = r.querySelector('.unit-label').textContent;

        const template = document.getElementById('ingredientRowTemplate');
        const clone = template.content.cloneNode(true);
        const row = clone.querySelector('.ingredient-row');
        
        const sel = row.querySelector('select');
        const inp = row.querySelector('input');
        const unit = row.querySelector('.unit-label');
        const deleteBtn = row.querySelector('button');

        sel.name = `recipes[${targetSizeId}][ingredient_id][]`;
        inp.name = `recipes[${targetSizeId}][quantity][]`;
        deleteBtn.setAttribute('onclick', `removeRow(this, ${targetSizeId})`);

        sel.value = selVal;
        inp.value = qtyVal;
        unit.textContent = unitVal;

        targetContainer.appendChild(row);
    });

    updateBadgeCount(targetSizeId);

    // Đồng thời sao chép cả bảng mức đường từ size Mặc định (0) sang Size này
    const defaultSugarSelect = document.querySelector('select[name="sugar_levels[0][inventory_id]"]');
    const targetSugarSelect = document.querySelector(`select[name="sugar_levels[${targetSizeId}][inventory_id]"]`);
    if (defaultSugarSelect && targetSugarSelect) {
        targetSugarSelect.value = defaultSugarSelect.value;
        updateSugarUnitLabel(targetSugarSelect, targetSizeId);
    }
    document.querySelectorAll('.sugar-input-0').forEach(defInput => {
        const pct = defInput.getAttribute('data-percent');
        const targetInput = document.querySelector(`.sugar-input-${targetSizeId}[data-percent="${pct}"]`);
        if (targetInput) {
            targetInput.value = defInput.value;
        }
    });
}

function updateSugarUnitLabel(selectElement, sizeId) {
    let unit = '--';
    if (selectElement.selectedIndex > 0) {
        unit = selectElement.options[selectElement.selectedIndex].getAttribute('data-unit') || '--';
    }
    document.querySelectorAll('.sugar-unit-' + sizeId).forEach(el => {
        el.textContent = unit;
    });
}

function autoCalculateSugar(sizeId) {
    const input100 = document.querySelector(`.sugar-input-${sizeId}[data-percent="100"]`);
    const val100 = parseFloat(input100 ? input100.value : 0);
    if (isNaN(val100) || val100 <= 0) {
        alert('Vui lòng nhập định lượng ở ô "100% Đường" trước, sau đó bấm nút này để hệ thống tự động tính các mức còn lại!');
        if (input100) input100.focus();
        return;
    }

    document.querySelectorAll(`.sugar-input-${sizeId}`).forEach(input => {
        const pct = parseInt(input.getAttribute('data-percent'));
        if (pct === 0) {
            input.value = 0;
        } else {
            const calculated = Math.round((val100 * pct / 100) * 100) / 100;
            input.value = calculated;
        }
    });
}

// Khởi tạo nhãn đơn vị đường khi tải trang
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.sugar-ing-select').forEach(select => {
        const matches = select.name.match(/sugar_levels\[(\d+)\]/);
        if (matches && matches[1]) {
            updateSugarUnitLabel(select, matches[1]);
        }
    });
});
</script>
