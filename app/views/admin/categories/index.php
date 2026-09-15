<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="font-serif fw-bold text-dark mb-0">Quản lý Danh mục</h4>
    <button class="btn btn-forest fw-medium px-4" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
        <i class="fa-solid fa-plus me-2"></i> Thêm danh mục
    </button>
</div>

<?php if(isset($_SESSION['flash_success'])): ?>
    <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
        <i class="fa-solid fa-check-circle me-2"></i> <?= $_SESSION['flash_success'] ?>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<?php if(isset($_SESSION['flash_error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
        <i class="fa-solid fa-triangle-exclamation me-2"></i> <?= $_SESSION['flash_error'] ?>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 fw-medium border-0 rounded-top-left">ID</th>
                        <th class="py-3 fw-medium border-0">Tên danh mục</th>
                        <th class="py-3 fw-medium border-0">Mô tả</th>
                        <th class="py-3 fw-medium border-0 text-center">Số lượng SP</th>
                        <th class="pe-4 py-3 fw-medium border-0 text-end rounded-top-right">Hành động</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <?php if(empty($categories)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Chưa có danh mục nào.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($categories as $cat): ?>
                        <tr>
                            <td class="ps-4 fw-bold text-muted">#<?= $cat['id'] ?></td>
                            <td>
                                <div class="fw-bold text-dark"><?= htmlspecialchars($cat['name']) ?></div>
                            </td>
                            <td>
                                <div class="small text-muted" style="max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <?= htmlspecialchars($cat['description'] ?? 'Không có mô tả') ?>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-forest bg-opacity-10 text-white rounded-pill px-3 py-2"><?= $cat['products_count'] ?> SP</span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <button class="btn btn-sm btn-outline-secondary" title="Sửa" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editCategoryModal"
                                            onclick="fillEditModal(<?= $cat['id'] ?>, '<?= htmlspecialchars(addslashes($cat['name'])) ?>', '<?= htmlspecialchars(addslashes($cat['description'] ?? '')) ?>')">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <form action="admin.php?route=categories&action=delete" method="POST" class="m-0" onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');">
                                        <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa" <?= $cat['products_count'] > 0 ? 'disabled' : '' ?>>
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Thêm Danh mục -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <form action="admin.php?route=categories&action=store" method="POST">
                <div class="modal-header border-bottom-0 pb-0 px-4 pt-4">
                    <h5 class="modal-title font-serif fw-bold text-forest">Thêm Danh Mục Mới</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4 py-4">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-medium mb-1">Tên danh mục <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control shadow-none border-secondary border-opacity-25" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-medium mb-1">Mô tả</label>
                        <textarea name="description" class="form-control shadow-none border-secondary border-opacity-25" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-forest px-4">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Sửa Danh mục -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <form action="admin.php?route=categories&action=update" method="POST">
                <input type="hidden" name="id" id="edit_cat_id">
                <div class="modal-header border-bottom-0 pb-0 px-4 pt-4">
                    <h5 class="modal-title font-serif fw-bold text-forest">Sửa Danh Mục</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4 py-4">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-medium mb-1">Tên danh mục <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="edit_cat_name" class="form-control shadow-none border-secondary border-opacity-25" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-medium mb-1">Mô tả</label>
                        <textarea name="description" id="edit_cat_desc" class="form-control shadow-none border-secondary border-opacity-25" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-forest px-4">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function fillEditModal(id, name, desc) {
    document.getElementById('edit_cat_id').value = id;
    document.getElementById('edit_cat_name').value = name;
    document.getElementById('edit_cat_desc').value = desc;
}
</script>
