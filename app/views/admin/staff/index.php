<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="font-serif fw-bold text-dark mb-0">Danh sách Nhân viên</h4>
    <button class="btn btn-forest fw-medium px-4" data-bs-toggle="modal" data-bs-target="#addStaffModal">
        <i class="fa-solid fa-plus me-2"></i> Thêm nhân viên
    </button>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-4 py-3 fw-medium border-0 rounded-top-left">Nhân viên</th>
                        <th class="py-3 fw-medium border-0">Username</th>
                        <th class="py-3 fw-medium border-0">Liên hệ</th>
                        <th class="py-3 fw-medium border-0">Vai trò</th>
                        <th class="py-3 fw-medium border-0">Ngày thêm</th>
                        <th class="pe-4 py-3 fw-medium border-0 text-end rounded-top-right">Trạng thái</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <?php if(empty($staffs)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Chưa có nhân viên nào.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($staffs as $staff): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-forest bg-opacity-10 text-forest rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        <i class="fa-solid fa-user-tie"></i>
                                    </div>
                                    <div class="fw-bold text-dark"><?= $staff['full_name'] ?></div>
                                </div>
                            </td>
                            <td><span class="text-muted">@<?= $staff['username'] ?></span></td>
                            <td>
                                <div class="small text-muted mb-1"><i class="fa-solid fa-phone me-1"></i> <?= $staff['phone'] ?? '---' ?></div>
                                <div class="small text-muted"><i class="fa-solid fa-envelope me-1"></i> <?= $staff['email'] ?? '---' ?></div>
                            </td>
                            <td>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-1 text-uppercase">Staff</span>
                            </td>
                            <td class="text-muted small"><?= date('d/m/Y', strtotime($staff['created_at'])) ?></td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end align-items-center gap-2">
                                    <?php if($staff['status'] == 1): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill mb-0 me-2"><i class="fa-solid fa-check me-1"></i> Đang làm</span>
                                        <form action="admin.php?route=staff&action=toggle_status" method="POST" class="m-0" onsubmit="return confirm('Khóa tài khoản nhân viên này?');">
                                            <input type="hidden" name="id" value="<?= $staff['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Khóa"><i class="fa-solid fa-lock"></i></button>
                                        </form>
                                    <?php else: ?>
                                        <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill mb-0 me-2"><i class="fa-solid fa-lock me-1"></i> Đã khóa</span>
                                        <form action="admin.php?route=staff&action=toggle_status" method="POST" class="m-0" onsubmit="return confirm('Mở khóa tài khoản nhân viên này?');">
                                            <input type="hidden" name="id" value="<?= $staff['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Mở khóa"><i class="fa-solid fa-unlock"></i></button>
                                        </form>
                                    <?php endif; ?>
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

<!-- Modal Thêm Nhân Viên -->
<div class="modal fade" id="addStaffModal" tabindex="-1" aria-labelledby="addStaffModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <form action="admin.php?route=staff&action=store" method="POST">
                <div class="modal-header border-bottom-0 pb-0 px-4 pt-4">
                    <h5 class="modal-title font-serif fw-bold text-forest" id="addStaffModalLabel">Thêm Nhân Viên Mới</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4 py-4">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-medium mb-1">Tên đăng nhập <span class="text-danger">*</span></label>
                        <input type="text" name="username" class="form-control shadow-none border-secondary border-opacity-25" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-medium mb-1">Mật khẩu <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control shadow-none border-secondary border-opacity-25" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-medium mb-1">Họ và tên <span class="text-danger">*</span></label>
                        <input type="text" name="full_name" class="form-control shadow-none border-secondary border-opacity-25" required>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small fw-medium mb-1">Số điện thoại</label>
                            <input type="text" name="phone" class="form-control shadow-none border-secondary border-opacity-25">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small fw-medium mb-1">Email</label>
                            <input type="email" name="email" class="form-control shadow-none border-secondary border-opacity-25">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-forest px-4">Lưu nhân viên</button>
                </div>
            </form>
        </div>
    </div>
</div>
