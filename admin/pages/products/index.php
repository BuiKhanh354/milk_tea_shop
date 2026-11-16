<?php
// admin/pages/products/index.php — Giao diện quản lý sản phẩm chuẩn gốc VAA THÉ
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="font-serif fw-bold text-dark mb-0">Quản lý sản phẩm</h4>
    <a href="<?= e(admin_url('product-create')) ?>" class="btn btn-forest fw-medium px-4 rounded-pill shadow-sm">
        <i class="fa-solid fa-plus me-2"></i> Thêm sản phẩm
    </a>
</div>

<?php if ($successMsg = get_flash('success')): ?>
    <div class="alert alert-success alert-dismissible fade show rounded-4 mb-4 border-0 shadow-sm" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i><?= e($successMsg) ?>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Filters -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        <form action="<?= e(admin_url('products')) ?>" method="GET">
            <input type="hidden" name="page" value="products">
            <div class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control bg-light border-0 shadow-none" placeholder="Tìm tên sản phẩm..." value="<?= e(get_param('search')) ?>">
                </div>
                <div class="col-md-3">
                    <select name="category_id" class="form-select bg-light border-0 shadow-none">
                        <option value="">Tất cả danh mục</option>
                        <?php foreach ($categories as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= get_param('category_id') == (string)$c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select bg-light border-0 shadow-none">
                        <option value="">Tất cả trạng thái</option>
                        <option value="1" <?= get_param('status') === '1' ? 'selected' : '' ?>>Đang bán</option>
                        <option value="0" <?= get_param('status') === '0' ? 'selected' : '' ?>>Ngừng bán</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-forest w-100 rounded-pill"><i class="fa-solid fa-filter me-2"></i> Lọc</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Products Table -->
<div class="admin-table-card">
    <div class="table-responsive">
        <table class="table mb-0 text-nowrap align-middle">
            <thead>
                <tr>
                    <th class="ps-4" style="width: 80px;">Hình ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Danh mục</th>
                    <th>Giá bán</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                    <th class="text-end pe-4">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($products)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-mug-hot fa-3x mb-3 text-secondary opacity-50"></i>
                            <p class="mb-0">Không tìm thấy sản phẩm nào phù hợp.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($products as $product): ?>
                    <tr>
                        <td class="ps-4">
                            <img src="<?= e($product['image']) ?>" alt="<?= e($product['name']) ?>" class="rounded-3 shadow-xs" width="48" height="48" style="object-fit: cover;">
                        </td>
                        <td class="fw-bold text-dark"><?= e($product['name']) ?></td>
                        <td><span class="badge bg-light text-dark border"><?= e($product['category']) ?></span></td>
                        <td class="fw-bold text-forest"><?= e($product['price']) ?></td>
                        <td>
                            <?php if ($product['status'] === '1'): ?>
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 fw-medium">Đang bán</span>
                            <?php else: ?>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-2 fw-medium">Ngừng bán</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-muted"><?= e($product['date']) ?></td>
                        <td class="text-end pe-4">
                            <a href="<?= e(url('product-detail', ['id' => $product['id']])) ?>" target="_blank" class="btn btn-sm btn-light border shadow-none me-1" title="Xem trên website">
                                <i class="fa-solid fa-arrow-up-right-from-square text-forest"></i>
                            </a>
                            <a href="<?= e(admin_url('product-edit', ['id' => $product['id']])) ?>" class="btn btn-sm btn-light border shadow-none me-1" title="Xem/Sửa">
                                <i class="fa-solid fa-pen text-muted"></i>
                            </a>
                            <a href="<?= e(admin_url('products', ['action' => 'toggle', 'id' => $product['id']])) ?>" class="btn btn-sm btn-light border shadow-none me-1" title="Ẩn/Hiện">
                                <i class="fa-solid <?= $product['status'] === '1' ? 'fa-eye-slash' : 'fa-eye' ?> text-muted"></i>
                            </a>
                            <a href="<?= e(admin_url('products', ['action' => 'delete', 'id' => $product['id']])) ?>" class="btn btn-sm btn-light border text-danger shadow-none" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                                <i class="fa-solid fa-trash-can"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if (isset($totalPages) && $totalPages > 1): ?>
    <?php 
        $queryArr = ['page' => 'products'];
        if (get_param('search') !== '') $queryArr['search'] = get_param('search');
        if (get_param('category_id') !== '') $queryArr['category_id'] = get_param('category_id');
        if (get_param('status') !== '') $queryArr['status'] = get_param('status');
        $curP = $productPage ?? 1;
    ?>
    <div class="card-footer bg-white border-top py-3 px-4 d-flex align-items-center justify-content-between">
        <span class="text-muted small">Hiển thị trang <?= $curP ?> của <?= $totalPages ?> (Tổng <?= $total ?> sản phẩm)</span>
        <nav aria-label="Page navigation">
            <ul class="pagination pagination-sm mb-0">
                <li class="page-item <?= $curP <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link <?= $curP <= 1 ? '' : 'text-forest' ?>" href="<?= $curP > 1 ? e(admin_url('products', array_merge($queryArr, ['p' => $curP - 1]))) : '#' ?>">Trước</a>
                </li>
                
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i === $curP ? 'active' : '' ?>">
                        <a class="page-link <?= $i === $curP ? 'bg-forest border-forest text-white' : 'text-forest' ?>" href="<?= e(admin_url('products', array_merge($queryArr, ['p' => $i]))) ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                
                <li class="page-item <?= $curP >= $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link <?= $curP >= $totalPages ? '' : 'text-forest' ?>" href="<?= $curP < $totalPages ? e(admin_url('products', array_merge($queryArr, ['p' => $curP + 1]))) : '#' ?>">Sau</a>
                </li>
            </ul>
        </nav>
    </div>
    <?php endif; ?>
</div>
