<!-- admin/pages/products/index.php -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="font-serif fw-bold text-forest mb-0">Danh Sách Sản Phẩm</h3>
    <a href="<?= e(admin_url('product-create')) ?>" class="btn btn-forest rounded-pill px-4">
        <i class="fa-solid fa-plus me-2"></i> Thêm Sản Phẩm Mới
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead class="text-muted small border-bottom">
                <tr>
                    <th>Hình ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Danh mục</th>
                    <th>Giá bán</th>
                    <th>Trạng thái</th>
                    <th class="text-end">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $p): ?>
                <tr>
                    <td>
                        <img src="<?= e(product_image($p['image'])) ?>" alt="" class="rounded-3 shadow-xs" style="width: 50px; height: 50px; object-fit: cover;">
                    </td>
                    <td class="fw-bold text-forest"><?= e($p['name']) ?></td>
                    <td><span class="badge bg-light text-dark"><?= e($p['category_name'] ?? 'Chưa phân loại') ?></span></td>
                    <td class="fw-bold text-caramel"><?= money($p['price']) ?></td>
                    <td>
                        <a href="<?= e(admin_url('products', ['action' => 'toggle-status', 'id' => $p['id']])) ?>" class="badge text-decoration-none <?= $p['status'] == 1 ? 'bg-success' : 'bg-secondary' ?>">
                            <?= $p['status'] == 1 ? 'Hiển thị' : 'Đã ẩn' ?>
                        </a>
                    </td>
                    <td class="text-end">
                        <a href="<?= e(admin_url('product-edit', ['id' => $p['id']])) ?>" class="btn btn-sm btn-outline-forest me-1">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <a href="<?= e(admin_url('products', ['action' => 'delete', 'id' => $p['id']])) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bạn có chắc muốn xoá sản phẩm này?')">
                            <i class="fa-solid fa-trash-can"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

