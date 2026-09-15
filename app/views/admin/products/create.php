<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="admin.php?route=products" class="btn btn-light border shadow-none">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h4 class="font-serif fw-bold text-dark mb-0">Thêm sản phẩm mới</h4>
    </div>
</div>

<div class="row">
    <div class="col-12 col-xl-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <form action="admin.php?route=products&action=store" method="POST" enctype="multipart/form-data">
                    <div class="row g-4">
                        <div class="col-12">
                            <label class="form-label fw-medium text-dark">Tên sản phẩm <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control bg-light border-0 shadow-none" required placeholder="Nhập tên sản phẩm...">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark">Danh mục <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select bg-light border-0 shadow-none" required>
                                <option value="">-- Chọn danh mục --</option>
                                <?php foreach($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark">Giá bán (VNĐ) <span class="text-danger">*</span></label>
                            <input type="text" name="price" class="form-control bg-light border-0 shadow-none" required placeholder="Ví dụ: 35000">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-medium text-dark">Hình ảnh</label>
                            <input type="file" name="image" class="form-control bg-light border-0 shadow-none" accept="image/*">
                            <div class="form-text">Định dạng JPG, PNG. Khuyến nghị 800x800px.</div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-medium text-dark">Mô tả</label>
                            <textarea name="description" class="form-control bg-light border-0 shadow-none" rows="4" placeholder="Mô tả chi tiết sản phẩm..."></textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-medium text-dark">Trạng thái</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input shadow-none" type="checkbox" role="switch" name="status" value="1" id="statusSwitch" checked>
                                <label class="form-check-label" for="statusSwitch">Đang bán</label>
                            </div>
                        </div>

                        <div class="col-12 text-end mt-5">
                            <a href="admin.php?route=products" class="btn btn-light border me-2 px-4 fw-medium">Hủy</a>
                            <button type="submit" class="btn btn-forest px-5 fw-medium">Lưu sản phẩm</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
