<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="font-serif fw-bold text-dark mb-1">CÔNG THỨC SẢN PHẨM</h4>
        <p class="text-muted fst-italic small mb-0">"Định lượng nguyên liệu cho từng sản phẩm."</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 fw-medium border-0 rounded-top-left" style="width: 80px;">Hình</th>
                        <th class="py-3 fw-medium border-0">Tên Sản Phẩm</th>
                        <th class="py-3 fw-medium border-0">Danh Mục</th>
                        <th class="py-3 fw-medium border-0 text-center">Số lượng nguyên liệu</th>
                        <th class="pe-4 py-3 fw-medium border-0 rounded-top-right text-end">Hành động</th>
                    </tr>
                </thead>
                <tbody class="border-top-0 bg-white">
                    <?php if(empty($products)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Chưa có sản phẩm nào.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($products as $p): ?>
                        <tr>
                            <td class="ps-4 py-3">
                                <img src="<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" class="rounded-3 object-fit-cover" width="48" height="48" onerror="this.src='https://via.placeholder.com/48x48?text=SP'">
                            </td>
                            <td>
                                <div class="fw-bold text-dark"><?= htmlspecialchars($p['name']) ?></div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border px-2 py-1"><?= htmlspecialchars($p['category']) ?></span>
                            </td>
                            <td class="text-center">
                                <?php if($p['ingredients_count'] > 0): ?>
                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                                        <?= $p['ingredients_count'] ?> nguyên liệu
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">
                                        Chưa có công thức
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="pe-4 text-end">
                                <a href="admin.php?route=product_ingredients&action=edit&id=<?= $p['id'] ?>" class="btn btn-sm <?= $p['ingredients_count'] > 0 ? 'btn-outline-primary' : 'btn-primary' ?> px-3 shadow-none rounded-pill">
                                    <i class="fa-solid <?= $p['ingredients_count'] > 0 ? 'fa-pen' : 'fa-plus' ?> me-1"></i> 
                                    <?= $p['ingredients_count'] > 0 ? 'Sửa công thức' : 'Tạo công thức' ?>
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
