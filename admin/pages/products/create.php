<?php
// admin/pages/products/create.php
$categories = db()->fetchAll("SELECT * FROM categories ORDER BY id ASC");
$title = 'Thêm sản phẩm mới';
$errors = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $categoryId = (int)($_POST['category_id'] ?? 0);
    $price = (float)($_POST['price'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $image = trim($_POST['image'] ?? '');

    if ($name === '') $errors[] = 'Vui lòng nhập tên sản phẩm.';
    if ($price <= 0) $errors[] = 'Giá sản phẩm phải lớn hơn 0.';

    if (empty($errors)) {
        db()->execute("INSERT INTO products (name, category_id, price, description, image, status, created_at) VALUES (?, ?, ?, ?, ?, 1, NOW())",
            array($name, $categoryId, $price, $description, $image)
        );
        redirect(admin_url('products'));
    }
}
?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
            <h4 class="font-serif fw-bold text-forest mb-4">Thêm Sản Phẩm Mới</h4>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger rounded-3 mb-4">
                    <ul class="mb-0">
                        <?php foreach ($errors as $err): ?>
                            <li><?= e($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?= e(admin_url('product-create')) ?>" method="POST">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Tên sản phẩm *</label>
                    <input type="text" name="name" class="form-control" required placeholder="Trà Sữa Ô Long Nướng">
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Danh mục</label>
                        <select name="category_id" class="form-select">
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>"><?= e($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Giá bán (VNĐ) *</label>
                        <input type="number" name="price" class="form-control" required min="1000" step="500" placeholder="35000">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">URL Hình ảnh</label>
                    <input type="text" name="image" class="form-control" placeholder="https://... hoặc uploads/...">
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold">Mô tả sản phẩm</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Mô tả hương vị, đặc trưng..."></textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-forest rounded-pill px-4 fw-bold">Lưu Sản Phẩm</button>
                    <a href="<?= e(admin_url('products')) ?>" class="btn btn-light rounded-pill px-4">Huỷ</a>
                </div>
            </form>
        </div>
    </div>
</div>

