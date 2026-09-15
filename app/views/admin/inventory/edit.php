<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="font-serif fw-bold text-dark mb-0">Sửa Nguyên liệu</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="admin.php?route=inventory" class="text-muted text-decoration-none">Kho</a></li>
                <li class="breadcrumb-item active">Sửa thông tin</li>
            </ol>
        </nav>
    </div>
    <a href="admin.php?route=inventory" class="btn btn-light border">
        <i class="fa-solid fa-arrow-left me-2"></i> Quay lại
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <!-- Chú ý: Không cho sửa quantity ở đây -->
        <div class="alert alert-light border border-warning border-opacity-25 rounded-3 mb-4 d-flex align-items-center">
            <i class="fa-solid fa-circle-info text-warning me-3 fs-4"></i>
            <div>
                <strong>Lưu ý quan trọng:</strong> 
                Để đảm bảo tính chính xác của Lịch sử Kho, bạn không thể trực tiếp sửa "Số lượng tồn" tại đây. 
                Vui lòng sử dụng chức năng <strong>Nhập kho</strong> hoặc <strong>Xuất kho</strong> để thay đổi số lượng.
            </div>
        </div>

        <form action="admin.php?route=inventory&action=update" method="POST">
            <input type="hidden" name="id" value="<?= $item['id'] ?>">
            
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label text-muted small fw-medium">Tên nguyên liệu <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control shadow-none" required value="<?= htmlspecialchars($item['ingredient_name']) ?>">
                </div>
                
                <div class="col-md-6">
                    <label class="form-label text-muted small fw-medium">Đơn vị tính <span class="text-danger">*</span></label>
                    <select name="unit" class="form-select shadow-none" required>
                        <option value="kg" <?= $item['unit'] == 'kg' ? 'selected' : '' ?>>kg (Kilogram)</option>
                        <option value="g" <?= $item['unit'] == 'g' ? 'selected' : '' ?>>g (Gram)</option>
                        <option value="L" <?= $item['unit'] == 'L' ? 'selected' : '' ?>>L (Lít)</option>
                        <option value="ml" <?= $item['unit'] == 'ml' ? 'selected' : '' ?>>ml (Mililít)</option>
                        <option value="cái" <?= $item['unit'] == 'cái' ? 'selected' : '' ?>>cái (Đơn vị đếm)</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-muted small fw-medium">Mức tồn tối thiểu <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" name="min_quantity" class="form-control shadow-none" required min="0" step="0.01" value="<?= floatval($item['min_quantity']) ?>">
                        <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-bell"></i></span>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-muted small fw-medium">Giá nhập tham khảo</label>
                    <div class="input-group">
                        <input type="number" name="price" class="form-control shadow-none" min="0" step="1000" value="<?= floatval($item['price']) ?>">
                        <span class="input-group-text bg-light text-muted">₫</span>
                    </div>
                </div>
                
                <div class="col-12">
                    <label class="form-label text-muted small fw-medium">Tồn kho hiện tại</label>
                    <div class="form-control bg-light text-muted" style="cursor: not-allowed;">
                        <?= floatval($item['quantity']) ?> <?= htmlspecialchars($item['unit']) ?> 
                        <span class="small ms-2 text-danger fst-italic">(Chỉ đọc)</span>
                    </div>
                </div>
            </div>

            <hr class="my-4" style="border-color: rgba(0,0,0,0.05);">

            <div class="d-flex justify-content-end gap-2">
                <a href="admin.php?route=inventory" class="btn btn-light px-4">Hủy</a>
                <button type="submit" class="btn btn-forest px-4">Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>
