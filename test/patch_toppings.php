<?php
$file = 'app/views/admin/toppings/index.php';
$content = file_get_contents($file);

// Add the Modals HTML and JS at the end of the file
$modalHTML = <<<HTML

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
HTML;

$content .= $modalHTML;

// Fix topping add button
$content = str_replace('<button class="btn btn-sm btn-caramel rounded-pill px-3"><i class="fa-solid fa-plus me-1"></i> Thêm Topping</button>',
'<button class="btn btn-sm btn-caramel rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#toppingModal" onclick="openToppingModal()"><i class="fa-solid fa-plus me-1"></i> Thêm Topping</button>', $content);

// Fix size add button
$content = str_replace('<button class="btn btn-sm btn-caramel rounded-pill px-3"><i class="fa-solid fa-plus me-1"></i> Thêm Size</button>',
'<button class="btn btn-sm btn-caramel rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#sizeModal" onclick="openSizeModal()"><i class="fa-solid fa-plus me-1"></i> Thêm Size</button>', $content);


// Replace static row buttons with dynamic ones in Toppings loop
$toppingActions = <<<HTML
<td class="text-end pe-4">
                                        <button class="btn btn-sm btn-light border shadow-none" title="Sửa" data-bs-toggle="modal" data-bs-target="#toppingModal" onclick="openToppingModal('<?= \$t['id'] ?>', '<?= htmlspecialchars(\$t['name'], ENT_QUOTES) ?>', '<?= \$t['price'] ?>', <?= \$t['status'] ? 'true' : 'false' ?>)"><i class="fa-solid fa-pen text-muted"></i></button>
                                        <a href="admin.php?route=toppings&action=deleteTopping&id=<?= \$t['id'] ?>" class="btn btn-sm btn-light border shadow-none" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa?')"><i class="fa-solid fa-trash text-danger"></i></a>
                                    </td>
HTML;
$content = preg_replace('/<td class="text-end pe-4">\s*<button class="btn btn-sm btn-light border shadow-none" title="Sửa"><i class="fa-solid fa-pen text-muted"><\/i><\/button>\s*<button class="btn btn-sm btn-light border shadow-none" title="Xóa"><i class="fa-solid fa-trash text-danger"><\/i><\/button>\s*<\/td>/', $toppingActions, $content, 1);


// Replace static row buttons with dynamic ones in Sizes loop
$sizeActions = <<<HTML
<td class="text-end pe-4">
                                        <button class="btn btn-sm btn-light border shadow-none" title="Sửa" data-bs-toggle="modal" data-bs-target="#sizeModal" onclick="openSizeModal('<?= \$s['id'] ?>', '<?= htmlspecialchars(\$s['name'], ENT_QUOTES) ?>', '<?= \$s['extra_price'] ?>', <?= \$s['status'] ? 'true' : 'false' ?>)"><i class="fa-solid fa-pen text-muted"></i></button>
                                        <a href="admin.php?route=toppings&action=deleteSize&id=<?= \$s['id'] ?>" class="btn btn-sm btn-light border shadow-none" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa?')"><i class="fa-solid fa-trash text-danger"></i></a>
                                    </td>
HTML;
$content = preg_replace('/<td class="text-end pe-4">\s*<button class="btn btn-sm btn-light border shadow-none" title="Sửa"><i class="fa-solid fa-pen text-muted"><\/i><\/button>\s*<\/td>/', $sizeActions, $content, 1);

file_put_contents($file, $content);
echo "Toppings View patched\n";
