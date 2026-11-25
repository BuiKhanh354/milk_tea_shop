<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="font-serif fw-bold text-dark mb-0">Cấu hình Công thức theo Size</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="<?= e(admin_url('product_ingredients')) ?>" class="text-muted text-decoration-none">Công thức SP</a></li>
                <li class="breadcrumb-item active text-forest"><?= e($product['name']) ?></li>
            </ol>
        </nav>
    </div>
    <a href="<?= e(admin_url('product_ingredients')) ?>" class="btn btn-light rounded-pill border">
        <i class="fa-solid fa-arrow-left me-2"></i> Quay lại
    </a>
</div>

<div class="row g-4">
    <!-- Cột trái: Thông tin sản phẩm -->
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 text-center mb-4 p-4">
            <img src="<?= e(product_image($product['image'])) ?>" alt="<?= e($product['name']) ?>" class="rounded-circle object-fit-cover mb-3 mx-auto" width="100" height="100">
            <h5 class="fw-bold text-dark mb-1"><?= e($product['name']) ?></h5>
            <span class="badge bg-light text-dark border px-2 py-1 mb-3 mx-auto"><?= e($product['category_name']) ?></span>
            <p class="text-muted small mb-0">Thiết lập định lượng nguyên liệu cho từng kích cỡ (Size) để hệ thống tự động trừ kho chính xác khi bán hàng.</p>
        </div>
    </div>
    
    <!-- Cột phải: Form công thức theo từng Size -->
    <div class="col-md-9">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <form action="<?= e(admin_url('product_ingredients', ['action' => 'store'])) ?>" method="POST" id="recipeForm">
                    <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">

                    <!-- Danh sách các Tab Size -->
                    <ul class="nav nav-pills mb-4 gap-2 border-bottom pb-3" id="sizeTabs" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link rounded-pill active px-4 fw-medium border" id="tab-btn-0" data-bs-toggle="pill" data-bs-target="#tab-size-0" type="button">
                                Mặc định (Chung)
                                <span class="badge bg-secondary ms-1 rounded-pill"><?= count($groupedRecipes[0] ?? []) ?></span>
                            </button>
                        </li>
                        <?php foreach ($sizes as $s): ?>
                        <li class="nav-item">
                            <button class="nav-link rounded-pill px-4 fw-medium border" id="tab-btn-<?= (int)$s['id'] ?>" data-bs-toggle="pill" data-bs-target="#tab-size-<?= (int)$s['id'] ?>" type="button">
                                Size <?= e($s['name']) ?>
                                <span class="badge bg-secondary ms-1 rounded-pill"><?= count($groupedRecipes[$s['id']] ?? []) ?></span>
                            </button>
                        </li>
                        <?php endforeach; ?>
                    </ul>

                    <!-- Nội dung các Tab -->
                    <div class="tab-content" id="sizeTabsContent">
                        <!-- Tab 0: Mặc định -->
                        <div class="tab-pane fade show active" id="tab-size-0" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold text-dark mb-0 font-serif">Nguyên liệu áp dụng chung</h6>
                                <button type="button" class="btn btn-sm btn-outline-forest rounded-pill px-3" onclick="addIngredientRow(0)">
                                    <i class="fa-solid fa-plus me-1"></i> Thêm nguyên liệu
                                </button>
                            </div>
                            <div id="list-size-0" class="ingredients-list mb-3">
                                <?php if (!empty($groupedRecipes[0])): ?>
                                    <?php foreach ($groupedRecipes[0] as $r): ?>
                                    <div class="row g-2 mb-2 align-items-center ingredient-row">
                                        <div class="col-7">
                                            <select name="recipes[0][ingredient_id][]" class="form-select border-secondary border-opacity-25 shadow-none" onchange="updateRowUnit(this)">
                                                <option value="">-- Chọn nguyên liệu --</option>
                                                <?php foreach ($all_ingredients as $ing): ?>
                                                    <option value="<?= (int)$ing['id'] ?>" data-unit="<?= e($ing['unit']) ?>" <?= $ing['id'] == $r['inventory_id'] ? 'selected' : '' ?>>
                                                        <?= e($ing['ingredient_name']) ?> (<?= e($ing['unit']) ?>)
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-4">
                                            <div class="input-group">
                                                <input type="number" step="0.01" min="0" name="recipes[0][quantity][]" class="form-control border-secondary border-opacity-25 shadow-none" value="<?= (float)$r['quantity'] ?>" placeholder="Định lượng">
                                                <span class="input-group-text bg-light text-muted small unit-label"><?= e($r['unit']) ?></span>
                                            </div>
                                        </div>
                                        <div class="col-1 text-end">
                                            <button type="button" class="btn btn-sm btn-outline-danger border-0" onclick="this.closest('.ingredient-row').remove()"><i class="fa-solid fa-trash"></i></button>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Các Tab Size cụ thể -->
                        <?php foreach ($sizes as $s): ?>
                        <div class="tab-pane fade" id="tab-size-<?= (int)$s['id'] ?>" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold text-dark mb-0 font-serif">Nguyên liệu cho Size <?= e($s['name']) ?></h6>
                                <button type="button" class="btn btn-sm btn-outline-forest rounded-pill px-3" onclick="addIngredientRow(<?= (int)$s['id'] ?>)">
                                    <i class="fa-solid fa-plus me-1"></i> Thêm nguyên liệu
                                </button>
                            </div>
                            <div id="list-size-<?= (int)$s['id'] ?>" class="ingredients-list mb-3">
                                <?php if (!empty($groupedRecipes[$s['id']])): ?>
                                    <?php foreach ($groupedRecipes[$s['id']] as $r): ?>
                                    <div class="row g-2 mb-2 align-items-center ingredient-row">
                                        <div class="col-7">
                                            <select name="recipes[<?= (int)$s['id'] ?>][ingredient_id][]" class="form-select border-secondary border-opacity-25 shadow-none" onchange="updateRowUnit(this)">
                                                <option value="">-- Chọn nguyên liệu --</option>
                                                <?php foreach ($all_ingredients as $ing): ?>
                                                    <option value="<?= (int)$ing['id'] ?>" data-unit="<?= e($ing['unit']) ?>" <?= $ing['id'] == $r['inventory_id'] ? 'selected' : '' ?>>
                                                        <?= e($ing['ingredient_name']) ?> (<?= e($ing['unit']) ?>)
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-4">
                                            <div class="input-group">
                                                <input type="number" step="0.01" min="0" name="recipes[<?= (int)$s['id'] ?>][quantity][]" class="form-control border-secondary border-opacity-25 shadow-none" value="<?= (float)$r['quantity'] ?>" placeholder="Định lượng">
                                                <span class="input-group-text bg-light text-muted small unit-label"><?= e($r['unit']) ?></span>
                                            </div>
                                        </div>
                                        <div class="col-1 text-end">
                                            <button type="button" class="btn btn-sm btn-outline-danger border-0" onclick="this.closest('.ingredient-row').remove()"><i class="fa-solid fa-trash"></i></button>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <hr class="my-4">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?= e(admin_url('product_ingredients')) ?>" class="btn btn-light rounded-pill px-4">Hủy</a>
                        <button type="submit" class="btn btn-forest rounded-pill px-4">
                            <i class="fa-solid fa-floppy-disk me-2"></i> Lưu cấu hình công thức
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<template id="ingredientRowTemplate">
    <div class="row g-2 mb-2 align-items-center ingredient-row">
        <div class="col-7">
            <select class="form-select border-secondary border-opacity-25 shadow-none" onchange="updateRowUnit(this)" required>
                <option value="">-- Chọn nguyên liệu --</option>
                <?php foreach ($all_ingredients as $ing): ?>
                    <option value="<?= (int)$ing['id'] ?>" data-unit="<?= e($ing['unit']) ?>"><?= e($ing['ingredient_name']) ?> (<?= e($ing['unit']) ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-4">
            <div class="input-group">
                <input type="number" step="0.01" min="0.01" class="form-control border-secondary border-opacity-25 shadow-none" placeholder="Định lượng" required>
                <span class="input-group-text bg-light text-muted small unit-label">--</span>
            </div>
        </div>
        <div class="col-1 text-end">
            <button type="button" class="btn btn-sm btn-outline-danger border-0" onclick="this.closest('.ingredient-row').remove()"><i class="fa-solid fa-trash"></i></button>
        </div>
    </div>
</template>

