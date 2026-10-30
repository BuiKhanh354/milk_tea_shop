<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="admin.php?route=products" class="btn btn-light border shadow-none">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h4 class="font-serif fw-bold text-dark mb-0">Chỉnh sửa sản phẩm</h4>
    </div>
</div>

<div class="row">
    <div class="col-12 col-xl-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <form action="admin.php?route=products&action=update" method="POST" enctype="multipart/form-data">
    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= $product['id'] ?>">
                    <div class="row g-4">
                        <div class="col-12">
                            <label class="form-label fw-medium text-dark">Tên sản phẩm <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control bg-light border-0 shadow-none" required value="<?= htmlspecialchars($product['name'] ?? '') ?>">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark">Danh mục <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select bg-light border-0 shadow-none" required>
                                <option value="">-- Chọn danh mục --</option>
                                <?php foreach($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= (isset($product['category_id']) && $product['category_id'] == $cat['id']) ? 'selected' : '' ?>><?= $cat['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark">Giá bán (VNĐ) <span class="text-danger">*</span></label>
                            <input type="text" name="price" class="form-control bg-light border-0 shadow-none" required value="<?= round($product['price'] ?? 0) ?>">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-medium text-dark">Hình ảnh mới (Tùy chọn)</label>
                            <input type="file" name="image" class="form-control bg-light border-0 shadow-none" accept="image/*">
                            <div class="form-text">Để trống nếu bạn muốn giữ nguyên hình ảnh cũ.</div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-medium text-dark">Mô tả</label>
                            <textarea name="description" class="form-control bg-light border-0 shadow-none" rows="4"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-medium text-dark">Trạng thái</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input shadow-none" type="checkbox" role="switch" name="status" value="1" id="statusSwitch" <?= (isset($product['status']) && $product['status'] == 1) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="statusSwitch">Đang bán</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-medium text-dark d-flex justify-content-between align-items-center">
                                <span>Topping áp dụng cho món này</span>
                                <span class="badge bg-light text-muted border fw-normal">Chọn các topping món này được phép dùng</span>
                            </label>
                            <div class="p-3 bg-light rounded-3 border">
                                <?php if (!empty($allToppings)): ?>
                                    <div class="row g-2">
                                        <?php foreach ($allToppings as $top): 
                                            $isChecked = in_array($top['id'], $assignedToppingIds ?? []);
                                        ?>
                                        <div class="col-md-6">
                                            <div class="form-check p-2 bg-white rounded border d-flex align-items-center gap-2 m-0">
                                                <input class="form-check-input ms-1 my-0 shadow-none" type="checkbox" name="toppings[]" value="<?= $top['id'] ?>" id="top_<?= $top['id'] ?>" <?= $isChecked ? 'checked' : '' ?>>
                                                <label class="form-check-label flex-grow-1 cursor-pointer small" for="top_<?= $top['id'] ?>" role="button">
                                                    <span class="fw-medium text-dark"><?= htmlspecialchars($top['name']) ?></span>
                                                    <span class="text-muted d-block">+<?= number_format($top['price'], 0, ',', '.') ?>đ</span>
                                                </label>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="mt-2 text-muted small">
                                        <i class="fa-solid fa-circle-info me-1"></i> Để trống nếu món này không cho phép thêm topping (ví dụ cà phê, nước đóng chai,...).
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted small mb-0">Chưa có topping nào trong hệ thống. <a href="admin.php?route=toppings" class="text-primary">Quản lý Topping</a></p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-12 text-end mt-5">
                            <a href="admin.php?route=products" class="btn btn-light border me-2 px-4 fw-medium">Hủy</a>
                            <button type="submit" class="btn btn-forest px-5 fw-medium">Cập nhật sản phẩm</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
