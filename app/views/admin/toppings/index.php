<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="font-serif fw-bold text-forest mb-0">Quản lý Topping & Size</h3>
    </div>

    <div class="row g-4">
        <!-- TOPPINGS -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-cube text-caramel me-2"></i> Toppings</h5>
                    <button class="btn btn-sm btn-caramel rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#toppingModal" onclick="openToppingModal()"><i class="fa-solid fa-plus me-1"></i> Thêm Topping</button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Tên Topping</th>
                                    <th>Giá (VNĐ)</th>
                                    <th>Trạng thái</th>
                                    <th class="text-end pe-4">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($toppings as $t): ?>
                                <tr>
                                    <td class="ps-4 fw-medium"><?= htmlspecialchars($t['name']) ?></td>
                                    <td class="text-forest fw-bold"><?= number_format($t['price'], 0, ',', '.') ?> đ</td>
                                    <td>
                                        <?php if ($t['status'] == 1): ?>
                                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Hoạt động</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3">Ẩn</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <button class="btn btn-sm btn-light border shadow-none" title="Sửa" data-bs-toggle="modal" data-bs-target="#toppingModal" onclick="openToppingModal('<?= $t['id'] ?>', '<?= htmlspecialchars($t['name'], ENT_QUOTES) ?>', '<?= $t['price'] ?>', <?= $t['status'] ? 'true' : 'false' ?>)"><i class="fa-solid fa-pen text-muted"></i></button>
                                        <a href="admin.php?route=toppings&action=deleteTopping&id=<?= $t['id'] ?>" class="btn btn-sm btn-light border shadow-none" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa?')"><i class="fa-solid fa-trash text-danger"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if (empty($toppings)): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Chưa có topping nào.</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- SIZES -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-expand text-caramel me-2"></i> Kích cỡ (Size)</h5>
                    <button class="btn btn-sm btn-caramel rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#sizeModal" onclick="openSizeModal()"><i class="fa-solid fa-plus me-1"></i> Thêm Size</button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Size</th>
                                    <th>Phụ thu (VNĐ)</th>
                                    <th>Trạng thái</th>
                                    <th class="text-end pe-4">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($sizes as $s): ?>
                                <tr>
                                    <td class="ps-4 fw-bold fs-5 text-dark"><?= htmlspecialchars($s['name']) ?></td>
                                    <td class="text-forest fw-bold">+<?= number_format($s['extra_price'], 0, ',', '.') ?> đ</td>
                                    <td>
                                        <?php if ($s['status'] == 1): ?>
                                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Hoạt động</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3">Ẩn</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <button class="btn btn-sm btn-light border shadow-none" title="Sửa" data-bs-toggle="modal" data-bs-target="#sizeModal" onclick="openSizeModal('<?= $s['id'] ?>', '<?= htmlspecialchars($s['name'], ENT_QUOTES) ?>', '<?= $s['extra_price'] ?>', <?= $s['status'] ? 'true' : 'false' ?>)"><i class="fa-solid fa-pen text-muted"></i></button>
                                        <a href="admin.php?route=toppings&action=deleteSize&id=<?= $s['id'] ?>" class="btn btn-sm btn-light border shadow-none" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa?')"><i class="fa-solid fa-trash text-danger"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if (empty($sizes)): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Chưa có size nào.</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Topping Modal -->
<div class="modal fade" id="toppingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow rounded-4">
            <form action="admin.php?route=toppings&action=storeTopping" method="POST" id="toppingForm">
                <input type="hidden" name="id" id="topping_id" value="">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold font-serif text-forest" id="toppingModalTitle">Thêm Topping</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-medium text-muted small">Tên Topping</label>
                        <input type="text" name="name" id="topping_name" class="form-control border-light shadow-none bg-light" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-muted small">Giá (VNĐ)</label>
                        <input type="number" name="price" id="topping_price" class="form-control border-light shadow-none bg-light" required>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="status" id="topping_status" value="1" checked>
                        <label class="form-check-label ms-2" for="topping_status">Hoạt động</label>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-forest rounded-pill px-4">Lưu lại</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Size Modal -->
<div class="modal fade" id="sizeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow rounded-4">
            <form action="admin.php?route=toppings&action=storeSize" method="POST" id="sizeForm">
                <input type="hidden" name="id" id="size_id" value="">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold font-serif text-forest" id="sizeModalTitle">Thêm Size</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-medium text-muted small">Tên Size</label>
                        <input type="text" name="name" id="size_name" class="form-control border-light shadow-none bg-light" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-muted small">Phụ thu (VNĐ)</label>
                        <input type="number" name="extra_price" id="size_extra_price" class="form-control border-light shadow-none bg-light" required>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="status" id="size_status" value="1" checked>
                        <label class="form-check-label ms-2" for="size_status">Hoạt động</label>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-forest rounded-pill px-4">Lưu lại</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openToppingModal(id = '', name = '', price = '', status = true) {
    document.getElementById('toppingForm').action = id ? 'admin.php?route=toppings&action=updateTopping' : 'admin.php?route=toppings&action=storeTopping';
    document.getElementById('toppingModalTitle').innerText = id ? 'Sửa Topping' : 'Thêm Topping';
    document.getElementById('topping_id').value = id;
    document.getElementById('topping_name').value = name;
    document.getElementById('topping_price').value = price;
    document.getElementById('topping_status').checked = status;
}

function openSizeModal(id = '', name = '', extra_price = '', status = true) {
    document.getElementById('sizeForm').action = id ? 'admin.php?route=toppings&action=updateSize' : 'admin.php?route=toppings&action=storeSize';
    document.getElementById('sizeModalTitle').innerText = id ? 'Sửa Size' : 'Thêm Size';
    document.getElementById('size_id').value = id;
    document.getElementById('size_name').value = name;
    document.getElementById('size_extra_price').value = extra_price;
    document.getElementById('size_status').checked = status;
}
</script>