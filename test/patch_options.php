<?php
$file = 'app/views/admin/options/index.php';
$content = file_get_contents($file);

// Add the Option Modal HTML and JS at the end of the file
$modalHTML = <<<HTML

<!-- Option Modal -->
<div class="modal fade" id="optionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow rounded-4">
            <form action="admin.php?route=options&action=store" method="POST" id="optionForm">
                <input type="hidden" name="id" id="option_id" value="">
                <input type="hidden" name="type" id="option_type" value="sugar">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold font-serif text-forest" id="optionModalTitle">Thêm tùy chọn</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-medium text-muted small">Tên hiển thị</label>
                        <input type="text" name="name" id="option_name" class="form-control border-light shadow-none bg-light" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-muted small">Giá trị (%)</label>
                        <input type="number" name="value" id="option_value" class="form-control border-light shadow-none bg-light" required>
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
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-forest rounded-pill px-4">Lưu lại</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openOptionModal(type, id = '', name = '', value = '', isDefault = false, status = true) {
    document.getElementById('optionForm').action = id ? 'admin.php?route=options&action=update' : 'admin.php?route=options&action=store';
    document.getElementById('optionModalTitle').innerText = id ? 'Sửa tùy chọn' : 'Thêm tùy chọn';
    document.getElementById('option_id').value = id;
    document.getElementById('option_type').value = type;
    document.getElementById('option_name').value = name;
    document.getElementById('option_value').value = value;
    document.getElementById('option_is_default').checked = isDefault;
    document.getElementById('option_status').checked = status;
}
</script>
HTML;

$content .= $modalHTML;

// Fix sugar buttons
$content = str_replace('<h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-cubes-stacked text-caramel me-2"></i> Mức đường (Sugar Level)</h5>
                    <button class="btn btn-sm btn-caramel rounded-pill px-3"><i class="fa-solid fa-plus me-1"></i> Thêm mức</button>',
'<h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-cubes-stacked text-caramel me-2"></i> Mức đường (Sugar Level)</h5>
                    <button class="btn btn-sm btn-caramel rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#optionModal" onclick="openOptionModal(\'sugar\')"><i class="fa-solid fa-plus me-1"></i> Thêm mức</button>', $content);

// Fix ice buttons
$content = str_replace('<h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-snowflake text-info me-2"></i> Mức đá (Ice Level)</h5>
                    <button class="btn btn-sm btn-caramel rounded-pill px-3"><i class="fa-solid fa-plus me-1"></i> Thêm mức</button>',
'<h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-snowflake text-info me-2"></i> Mức đá (Ice Level)</h5>
                    <button class="btn btn-sm btn-caramel rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#optionModal" onclick="openOptionModal(\'ice\')"><i class="fa-solid fa-plus me-1"></i> Thêm mức</button>', $content);


// Replace static row buttons with dynamic ones in Sugar loop
$sugarActions = <<<HTML
<td class="text-end pe-4">
                                        <button class="btn btn-sm btn-light border shadow-none" title="Sửa" data-bs-toggle="modal" data-bs-target="#optionModal" onclick="openOptionModal('sugar', '<?= \$s['id'] ?>', '<?= htmlspecialchars(\$s['name'], ENT_QUOTES) ?>', '<?= \$s['value'] ?>', <?= \$s['is_default'] ? 'true' : 'false' ?>, <?= \$s['status'] ? 'true' : 'false' ?>)"><i class="fa-solid fa-pen text-muted"></i></button>
                                        <a href="admin.php?route=options&action=delete&id=<?= \$s['id'] ?>" class="btn btn-sm btn-light border shadow-none" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa?')"><i class="fa-solid fa-trash text-danger"></i></a>
                                    </td>
HTML;
$content = preg_replace('/<td class="text-end pe-4">\s*<button class="btn btn-sm btn-light border shadow-none" title="Sửa"><i class="fa-solid fa-pen text-muted"><\/i><\/button>\s*<button class="btn btn-sm btn-light border shadow-none" title="Xóa"><i class="fa-solid fa-trash text-danger"><\/i><\/button>\s*<\/td>/', $sugarActions, $content, 1);

// Same for Ice loop
$iceActions = <<<HTML
<td class="text-end pe-4">
                                        <button class="btn btn-sm btn-light border shadow-none" title="Sửa" data-bs-toggle="modal" data-bs-target="#optionModal" onclick="openOptionModal('ice', '<?= \$i['id'] ?>', '<?= htmlspecialchars(\$i['name'], ENT_QUOTES) ?>', '<?= \$i['value'] ?>', <?= \$i['is_default'] ? 'true' : 'false' ?>, <?= \$i['status'] ? 'true' : 'false' ?>)"><i class="fa-solid fa-pen text-muted"></i></button>
                                        <a href="admin.php?route=options&action=delete&id=<?= \$i['id'] ?>" class="btn btn-sm btn-light border shadow-none" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa?')"><i class="fa-solid fa-trash text-danger"></i></a>
                                    </td>
HTML;
$content = preg_replace('/<td class="text-end pe-4">\s*<button class="btn btn-sm btn-light border shadow-none" title="Sửa"><i class="fa-solid fa-pen text-muted"><\/i><\/button>\s*<button class="btn btn-sm btn-light border shadow-none" title="Xóa"><i class="fa-solid fa-trash text-danger"><\/i><\/button>\s*<\/td>/', $iceActions, $content, 1);

file_put_contents($file, $content);
echo "Options View patched\n";
