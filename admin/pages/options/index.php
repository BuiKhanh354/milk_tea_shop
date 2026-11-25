<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="font-serif fw-bold text-dark mb-0">Tùy chọn (Đường & Đá)</h4>
</div>

<?php if ($successMsg = get_flash('success')): ?>
    <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
        <i class="fa-solid fa-check-circle me-2"></i> <?= e($successMsg) ?>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row g-4">
    <!-- SUGAR LEVELS -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-cubes-stacked text-warning me-2"></i> Mức đường (Sugar Level)</h5>
                <button type="button" data-bs-toggle="modal" data-bs-target="#optionModal" onclick="openOptionModal('sugar')" class="btn btn-sm btn-forest rounded-pill px-3"><i class="fa-solid fa-plus me-1"></i> Thêm mức</button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 fw-medium border-0">Tên hiển thị</th>
                                <th class="py-3 fw-medium border-0">Giá trị (%)</th>
                                <th class="py-3 fw-medium border-0">Trạng thái</th>
                                <th class="text-end pe-4 py-3 fw-medium border-0">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            <?php foreach ($sugarOptions as $s): ?>
                            <tr>
                                <td class="ps-4 fw-medium"><?= e($s['name']) ?></td>
                                <td><?= (int)$s['value'] ?>%</td>
                                <td>
                                    <?php if ($s['status'] == 1): ?>
                                        <?php if ($s['is_default'] == 1): ?>
                                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Mặc định</span>
                                        <?php else: ?>
                                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Hoạt động</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3">Ẩn</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4">
                                    <button class="btn btn-sm btn-light border shadow-none" title="Sửa" data-bs-toggle="modal" data-bs-target="#optionModal" onclick="openOptionModal('sugar', '<?= $s['id'] ?>', '<?= e(addslashes($s['name'])) ?>', '<?= $s['value'] ?>', <?= $s['is_default'] ? 'true' : 'false' ?>, <?= $s['status'] ? 'true' : 'false' ?>)"><i class="fa-solid fa-pen text-muted"></i></button>
                                    <a href="<?= e(admin_url('options', ['action' => 'delete', 'id' => $s['id']])) ?>" class="btn btn-sm btn-light border shadow-none" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa?')"><i class="fa-solid fa-trash text-danger"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($sugarOptions)): ?>
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Chưa có mức đường nào.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- ICE LEVELS -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid text-info me-2"></i> Mức đá (Ice Level)</h5>
                <button type="button" data-bs-toggle="modal" data-bs-target="#optionModal" onclick="openOptionModal('ice')" class="btn btn-sm btn-forest rounded-pill px-3"><i class="fa-solid fa-plus me-1"></i> Thêm mức</button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 fw-medium border-0">Tên hiển thị</th>
                                <th class="py-3 fw-medium border-0">Giá trị (%)</th>
                                <th class="py-3 fw-medium border-0">Trạng thái</th>
                                <th class="text-end pe-4 py-3 fw-medium border-0">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            <?php foreach ($iceOptions as $i): ?>
                            <tr>
                                <td class="ps-4 fw-medium"><?= e($i['name']) ?></td>
                                <td><?= (int)$i['value'] ?>%</td>
                                <td>
                                    <?php if ($i['status'] == 1): ?>
                                        <?php if ($i['is_default'] == 1): ?>
                                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Mặc định</span>
                                        <?php else: ?>
                                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Hoạt động</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3">Ẩn</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4">
                                    <button class="btn btn-sm btn-light border shadow-none" title="Sửa" data-bs-toggle="modal" data-bs-target="#optionModal" onclick="openOptionModal('ice', '<?= $i['id'] ?>', '<?= e(addslashes($i['name'])) ?>', '<?= $i['value'] ?>', <?= $i['is_default'] ? 'true' : 'false' ?>, <?= $i['status'] ? 'true' : 'false' ?>)"><i class="fa-solid fa-pen text-muted"></i></button>
                                    <a href="<?= e(admin_url('options', ['action' => 'delete', 'id' => $i['id']])) ?>" class="btn btn-sm btn-light border shadow-none" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa?')"><i class="fa-solid fa-trash text-danger"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($iceOptions)): ?>
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Chưa có mức đá nào.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Option Modal -->
<div class="modal fade" id="optionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <form action="<?= e(admin_url('options', ['action' => 'store'])) ?>" method="POST" id="optionForm">
                <input type="hidden" name="id" id="option_id" value="">
                <input type="hidden" name="type" id="option_type" value="sugar">
                <div class="modal-header border-0 pb-0 px-4 pt-4">
                    <h5 class="modal-title fw-bold font-serif text-forest" id="optionModalTitle">Thêm tùy chọn</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4 py-3">
                    <div class="mb-3">
                        <label class="form-label fw-medium text-muted small">Tên hiển thị <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="option_name" class="form-control border-secondary border-opacity-25 shadow-none" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-muted small">Giá trị (%) <span class="text-danger">*</span></label>
                        <input type="number" name="value" id="option_value" class="form-control border-secondary border-opacity-25 shadow-none" required>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_default" id="option_is_default" value="1">
                        <label class="form-check-label ms-2" for="option_is_default">Đặt làm mặc định</label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="status" id="option_status" value="1" checked>
                        <label class="form-check-label ms-2" for="option_status">Hoạt động</label>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-forest rounded-pill px-4">Lưu lại</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openOptionModal(type, id = '', name = '', value = '', isDefault = false, status = true) {
    document.getElementById('optionForm').action = id ? '<?= e(admin_url('options', ['action' => 'update'])) ?>' : '<?= e(admin_url('options', ['action' => 'store'])) ?>';
    document.getElementById('optionModalTitle').innerText = id ? 'Sửa tùy chọn' : 'Thêm tùy chọn';
    document.getElementById('option_id').value = id;
    document.getElementById('option_type').value = type;
    document.getElementById('option_name').value = name;
    document.getElementById('option_value').value = value;
    document.getElementById('option_is_default').checked = isDefault;
    document.getElementById('option_status').checked = status;
}
</script>

