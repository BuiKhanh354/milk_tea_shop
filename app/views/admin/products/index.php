<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="font-serif fw-bold text-dark mb-0">Quản lý sản phẩm</h4>
    <a href="admin.php?route=products&action=create" class="btn btn-forest fw-medium px-4">
        <i class="fa-solid fa-plus me-2"></i> Thêm sản phẩm
    </a>
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body">
        <form action="admin.php" method="GET">
            <input type="hidden" name="route" value="products">
            <div class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control bg-light border-0" placeholder="Tìm tên sản phẩm..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <select name="category_id" class="form-select bg-light border-0">
                        <option value="">Tất cả danh mục</option>
                        <?php 
                        require_once __DIR__ . '/../../../models/Category.php';
                        $catModel = new Category();
                        $cats = $catModel->getAll();
                        foreach ($cats as $c): 
                        ?>
                            <option value="<?= $c['id'] ?>" <?= (isset($_GET['category_id']) && $_GET['category_id'] == $c['id']) ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select bg-light border-0">
                        <option value="">Tất cả trạng thái</option>
                        <option value="1" <?= (isset($_GET['status']) && $_GET['status'] === '1') ? 'selected' : '' ?>>Đang bán</option>
                        <option value="0" <?= (isset($_GET['status']) && $_GET['status'] === '0') ? 'selected' : '' ?>>Ngừng bán</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-forest w-100"><i class="fa-solid fa-filter me-2"></i> Lọc</button>
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
                <?php foreach($products as $product): ?>
                <tr>
                    <td class="ps-4">
                        <img src="<?= $product['image'] ?>" alt="<?= $product['name'] ?>" class="rounded" width="48" height="48" style="object-fit: cover;">
                    </td>
                    <td class="fw-bold text-dark"><?= $product['name'] ?></td>
                    <td><span class="badge bg-light text-dark border"><?= $product['category'] ?></span></td>
                    <td class="fw-bold text-forest"><?= $product['price'] ?></td>
                    <td>
                        <?php if($product['status'] == '1'): ?>
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 fw-medium">Đang bán</span>
                        <?php else: ?>
                            <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-2 fw-medium">Ngừng bán</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-muted"><?= $product['date'] ?></td>
                    <td class="text-end pe-4">
                        <a href="admin.php?route=products&action=edit&id=<?= $product['id'] ?>" class="btn btn-sm btn-light border shadow-none me-1" data-bs-toggle="tooltip" title="Xem/Sửa">
                            <i class="fa-solid fa-pen text-muted"></i>
                        </a>
                        <a href="admin.php?route=products&action=toggle&id=<?= $product['id'] ?>" class="btn btn-sm btn-light border shadow-none me-1" data-bs-toggle="tooltip" title="Ẩn/Hiện">
                            <i class="fa-solid <?= $product['status'] == '1' ? 'fa-eye-slash' : 'fa-eye' ?> text-muted"></i>
                        </a>
                        <a href="admin.php?route=products&action=delete&id=<?= $product['id'] ?>" class="btn btn-sm btn-light border text-danger shadow-none" data-bs-toggle="tooltip" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                            <i class="fa-solid fa-trash-can"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
            <!-- Pagination -->
    <?php if (isset($totalPages) && $totalPages > 1): ?>
    <?php 
        $queryString = '';
        if (isset($_GET['search'])) $queryString .= '&search=' . urlencode($_GET['search']);
        if (isset($_GET['category_id'])) $queryString .= '&category_id=' . urlencode($_GET['category_id']);
        if (isset($_GET['status'])) $queryString .= '&status=' . urlencode($_GET['status']);
    ?>
    <div class="card-footer bg-white border-top py-3 px-4 d-flex align-items-center justify-content-between">
        <span class="text-muted small">Hiển thị trang <?= $currentPage ?> của <?= $totalPages ?></span>
        <nav aria-label="Page navigation">
            <ul class="pagination pagination-sm mb-0">
                <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link <?= $currentPage <= 1 ? '' : 'text-forest' ?>" href="<?= $currentPage > 1 ? 'admin.php?route=products' . $queryString . '&page=' . ($currentPage - 1) : '#' ?>">Trước</a>
                </li>
                
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                        <a class="page-link <?= $i === $currentPage ? 'bg-forest border-forest' : 'text-forest' ?>" href="admin.php?route=products<?= $queryString ?>&page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                
                <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link <?= $currentPage >= $totalPages ? '' : 'text-forest' ?>" href="<?= $currentPage < $totalPages ? 'admin.php?route=products' . $queryString . '&page=' . ($currentPage + 1) : '#' ?>">Sau</a>
                </li>
            </ul>
        </nav>
    </div>
    <?php endif; ?>
</div>
