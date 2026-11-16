<?php
// admin/pages/products/create.php — Thêm sản phẩm mới chuẩn gốc VAA THÉ
$title = 'Thêm sản phẩm mới';

$categories = db()->fetchAll("SELECT * FROM categories ORDER BY name ASC");
$allToppings = db()->fetchAll("SELECT * FROM toppings WHERE status = 1 ORDER BY name ASC");
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim(post_param('name'));
    $categoryId = (int)post_param('category_id');
    $price = (float)str_replace(['.', ','], '', post_param('price'));
    $description = trim(post_param('description'));
    $status = isset($_POST['status']) ? 1 : 0;
    $selectedToppings = isset($_POST['toppings']) && is_array($_POST['toppings']) ? $_POST['toppings'] : [];

    if ($name === '') $errors[] = 'Vui lòng nhập tên sản phẩm.';
    if ($price <= 0) $errors[] = 'Giá bán phải lớn hơn 0.';
    if ($categoryId <= 0) $errors[] = 'Vui lòng chọn danh mục sản phẩm.';

    $imageFileName = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../../public/assets/images/products/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imageFileName = time() . '_' . uniqid() . '.' . $ext;
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $imageFileName)) {
            $imageFileName = '';
        }
    }

    if (empty($errors)) {
        db()->execute(
            "INSERT INTO products (name, category_id, price, description, image, status, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())",
            [$name, $categoryId, $price, $description, $imageFileName, $status]
        );
        $productId = db()->insertId();

        if ($productId && !empty($selectedToppings)) {
            foreach ($selectedToppings as $topId) {
                $topId = (int)$topId;
                if ($topId > 0) {
                    db()->execute(
                        "INSERT IGNORE INTO product_toppings (product_id, topping_id) VALUES (?, ?)",
                        [$productId, $topId]
                    );
                }
            }
        }

        set_flash('success', 'Thêm sản phẩm thành công!');
        redirect(admin_url('products'));
    }
}
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="<?= e(admin_url('products')) ?>" class="btn btn-light border shadow-none rounded-circle" style="width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center;">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h4 class="font-serif fw-bold text-dark mb-0">Thêm sản phẩm mới</h4>
    </div>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger rounded-4 mb-4 border-0 shadow-sm">
        <ul class="mb-0">
            <?php foreach ($errors as $err): ?>
                <li><?= e($err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-12 col-xl-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4 p-md-5">
                <form action="<?= e(admin_url('product-create')) ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="row g-4">
                        <div class="col-12">
                            <label class="form-label fw-medium text-dark">Tên sản phẩm <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control bg-light border-0 shadow-none" required placeholder="Nhập tên sản phẩm..." value="<?= e(post_param('name')) ?>">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark">Danh mục <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select bg-light border-0 shadow-none" required>
                                <option value="">-- Chọn danh mục --</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= post_param('category_id') == (string)$cat['id'] ? 'selected' : '' ?>><?= e($cat['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark">Giá bán (VNĐ) <span class="text-danger">*</span></label>
                            <input type="number" name="price" class="form-control bg-light border-0 shadow-none" required placeholder="Ví dụ: 35000" value="<?= e(post_param('price')) ?>">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-medium text-dark">Hình ảnh</label>
                            <input type="file" name="image" class="form-control bg-light border-0 shadow-none" accept="image/*">
                            <div class="form-text">Định dạng JPG, PNG, WEBP. Khuyến nghị ảnh vuông tỉ lệ 1:1.</div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-medium text-dark">Mô tả</label>
                            <textarea name="description" class="form-control bg-light border-0 shadow-none" rows="4" placeholder="Mô tả chi tiết hương vị và điểm nổi bật của món..."><?= e(post_param('description')) ?></textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-medium text-dark">Trạng thái</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input shadow-none" type="checkbox" role="switch" name="status" value="1" id="statusSwitch" checked>
                                <label class="form-check-label" for="statusSwitch">Đang bán</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-medium text-dark d-flex justify-content-between align-items-center">
                                <span>Topping áp dụng cho món này</span>
                                <span class="badge bg-light text-muted border fw-normal">Chọn các topping món này được phép dùng</span>
                            </label>
                            <div class="p-3 bg-light rounded-4 border">
                                <?php if (!empty($allToppings)): ?>
                                    <div class="row g-2">
                                        <?php foreach ($allToppings as $top): ?>
                                        <div class="col-md-6">
                                            <div class="form-check p-2 bg-white rounded-3 border d-flex align-items-center gap-2 m-0">
                                                <input class="form-check-input ms-1 my-0 shadow-none" type="checkbox" name="toppings[]" value="<?= $top['id'] ?>" id="top_<?= $top['id'] ?>">
                                                <label class="form-check-label flex-grow-1 cursor-pointer small" for="top_<?= $top['id'] ?>" role="button">
                                                    <span class="fw-medium text-dark"><?= e($top['name']) ?></span>
                                                    <span class="text-muted d-block">+<?= money($top['price']) ?></span>
                                                </label>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="mt-2 text-muted small">
                                        <i class="fa-solid fa-circle-info me-1"></i> Để trống nếu món này không cho phép thêm topping (ví dụ cà phê, nước ép đóng chai...).
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted small mb-0">Chưa có topping nào trong hệ thống. <a href="<?= e(admin_url('toppings')) ?>" class="text-forest">Quản lý Topping</a></p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-12 text-end mt-4">
                            <a href="<?= e(admin_url('products')) ?>" class="btn btn-light border me-2 px-4 fw-medium rounded-pill">Hủy</a>
                            <button type="submit" class="btn btn-forest px-5 fw-medium rounded-pill shadow-sm">
                                <i class="fa-solid fa-floppy-disk me-2"></i>Lưu sản phẩm
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
