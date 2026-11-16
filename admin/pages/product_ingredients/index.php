<?php
if ($action === 'edit') {
    require __DIR__ . '/edit.php';
    return;
}
?>
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="font-serif fw-bold text-dark mb-1">CÔNG THỨC SẢN PHẨM</h4>
        <p class="text-muted fst-italic small mb-0">"Định lượng nguyên liệu cho từng sản phẩm."</p>
    </div>
</div>

<?php if ($successMsg = get_flash('success')): ?>
    <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
        <i class="fa-solid fa-check-circle me-2"></i> <?= e($successMsg) ?>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-4 py-3 fw-medium border-0" style="width: 70px;">Hình</th>
                        <th class="py-3 fw-medium border-0">Tên Sản Phẩm</th>
                        <th class="py-3 fw-medium border-0">Danh Mục</th>
                        <th class="py-3 fw-medium border-0 text-center">Số lượng nguyên liệu</th>
                        <th class="pe-4 py-3 fw-medium border-0 text-end">Hành động</th>
                    </tr>
                </thead>
                <tbody class="border-top-0 bg-white">
                    <?php if (empty($productsRaw)): ?>
                        <tr><td colspan="5" class="text-center py-5 text-muted">Chưa có sản phẩm nào.</td></tr>
                    <?php else: ?>
                        <?php foreach ($productsRaw as $p): ?>
                        <tr>
                            <td class="ps-4 py-3">
                                <img src="<?= e(product_image($p['image'])) ?>" alt="<?= e($p['name']) ?>" class="rounded-3 object-fit-cover" width="48" height="48">
                            </td>
                            <td>
                                <div class="fw-bold text-dark"><?= e($p['name']) ?></div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border px-2 py-1"><?= e($p['category_name']) ?></span>
                            </td>
                            <td class="text-center">
                                <?php if ($p['ingredients_count'] > 0): ?>
                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                                        <?= (int)$p['ingredients_count'] ?> nguyên liệu
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">
                                        Chưa có công thức
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="pe-4 text-end">
                                <a href="<?= e(admin_url('product_ingredients', ['action' => 'edit', 'id' => $p['id']])) ?>" class="btn btn-sm btn-outline-forest rounded-pill px-3 shadow-none">
                                    <i class="fa-solid fa-sliders me-1"></i> Định lượng
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
