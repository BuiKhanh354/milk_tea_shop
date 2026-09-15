<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="font-serif fw-bold text-dark mb-0">Thêm Nguyên liệu</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="admin.php?route=inventory" class="text-muted text-decoration-none">Kho</a></li>
                <li class="breadcrumb-item active">Thêm mới</li>
            </ol>
        </nav>
    </div>
    <a href="admin.php?route=inventory" class="btn btn-light border">
        <i class="fa-solid fa-arrow-left me-2"></i> Quay lại
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <form action="admin.php?route=inventory&action=store" method="POST">
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label text-muted small fw-medium">Tên nguyên liệu <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control shadow-none" required placeholder="VD: Trà Oolong">
                </div>
                
                <div class="col-md-6">
                    <label class="form-label text-muted small fw-medium">Đơn vị tính <span class="text-danger">*</span></label>
                    <select name="unit" class="form-select shadow-none" required>
                        <option value="">Chọn đơn vị</option>
                        <option value="kg">kg (Kilogram)</option>
                        <option value="g">g (Gram)</option>
                        <option value="L">L (Lít)</option>
                        <option value="ml">ml (Mililít)</option>
                        <option value="cái">cái (Đơn vị đếm)</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-muted small fw-medium">Mức tồn tối thiểu <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" name="min_quantity" class="form-control shadow-none" required min="0" step="0.01" value="0">
                        <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-bell"></i></span>
                    </div>
                    <div class="form-text small">Hệ thống sẽ cảnh báo khi tồn kho thấp hơn mức này.</div>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-muted small fw-medium">Giá nhập tham khảo</label>
                    <div class="input-group">
                        <input type="number" name="price" class="form-control shadow-none" min="0" step="1000" value="0">
                        <span class="input-group-text bg-light text-muted">₫</span>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label text-muted small fw-medium">Ghi chú</label>
                    <textarea name="note" class="form-control shadow-none" rows="3" placeholder="Ghi chú thêm về nguyên liệu này (không bắt buộc)"></textarea>
                </div>
            </div>

            <hr class="my-4" style="border-color: rgba(0,0,0,0.05);">

            <div class="d-flex justify-content-end gap-2">
                <a href="admin.php?route=inventory" class="btn btn-light px-4">Hủy</a>
                <button type="submit" class="btn btn-forest px-4">Lưu nguyên liệu</button>
            </div>
        </form>
    </div>
</div>
