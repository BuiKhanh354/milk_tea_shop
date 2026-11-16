<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h4 class="font-serif fw-bold text-dark mb-0">Quản lý ca làm việc</h4>
    <div class="d-flex gap-2">
        <a href="<?= e(admin_url('shift_sessions')) ?>" class="btn btn-outline-secondary rounded-pill fw-medium px-3">
            <i class="fa-solid fa-cash-register me-2"></i> Lịch sử Mở / Kết ca
        </a>
        <button class="btn btn-outline-forest rounded-pill fw-medium px-3" data-bs-toggle="modal" data-bs-target="#shiftListModal">
            <i class="fa-solid fa-list me-2"></i> Danh mục ca
        </button>
        <button class="btn btn-forest rounded-pill fw-medium px-4" data-bs-toggle="modal" data-bs-target="#assignShiftModal">
            <i class="fa-solid fa-user-plus me-2"></i> Phân công
        </button>
    </div>
</div>

<?php if ($errorMsg = get_flash('error')): ?>
<div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
    <i class="fa-solid fa-triangle-exclamation me-2"></i> <?= e($errorMsg) ?>
    <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if ($successMsg = get_flash('success')): ?>
<div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
    <i class="fa-solid fa-circle-check me-2"></i> <?= e($successMsg) ?>
    <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- Form Filter -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-3">
        <form action="<?= e(admin_url('shifts')) ?>" method="GET" class="row g-3 align-items-end">
            <input type="hidden" name="page" value="shifts">
            <div class="col-md-4">
                <label class="form-label text-muted small mb-1">Từ ngày</label>
                <input type="date" name="start_date" class="form-control bg-light border-0 shadow-none" value="<?= e($startDate) ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label text-muted small mb-1">Đến ngày</label>
                <input type="date" name="end_date" class="form-control bg-light border-0 shadow-none" value="<?= e($endDate) ?>">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-forest w-100"><i class="fa-solid fa-filter me-2"></i> Lọc danh sách</button>
            </div>
        </form>
    </div>
</div>

<!-- Assignments Table -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-4 py-3 fw-medium border-0">Ngày</th>
                        <th class="py-3 fw-medium border-0">Nhân viên</th>
                        <th class="py-3 fw-medium border-0">Ca làm việc</th>
                        <th class="py-3 fw-medium border-0">Thời gian</th>
                        <th class="py-3 fw-medium border-0">Trạng thái</th>
                        <th class="text-end pe-4 py-3 fw-medium border-0">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <?php if (empty($assignments)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Chưa có dữ liệu phân công</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($assignments as $a): ?>
                        <tr>
                            <td class="ps-4 fw-medium"><?= date('d/m/Y', strtotime($a['work_date'])) ?></td>
                            <td class="fw-bold text-dark"><?= e($a['employee_name']) ?></td>
                            <td><span class="badge bg-secondary bg-opacity-10 text-dark"><?= e($a['shift_name']) ?></span></td>
                            <td class="small text-muted"><?= date('H:i', strtotime($a['start_time'])) ?> - <?= date('H:i', strtotime($a['end_time'])) ?></td>
                            <td>
                                <?php
                                $badgeClass = 'bg-secondary';
                                $statusText = 'Chờ làm';
                                if ($a['status'] === 'assigned') { $badgeClass = 'bg-warning text-dark'; $statusText = 'Chờ làm'; }
                                if ($a['status'] === 'in_progress') { $badgeClass = 'bg-primary text-white'; $statusText = 'Đang làm'; }
                                if ($a['status'] === 'completed') { $badgeClass = 'bg-success text-white'; $statusText = 'Hoàn thành'; }
                                if ($a['status'] === 'absent') { $badgeClass = 'bg-danger text-white'; $statusText = 'Vắng mặt'; }
                                if ($a['status'] === 'cancelled') { $badgeClass = 'bg-dark text-white'; $statusText = 'Đã hủy'; }
                                ?>
                                <span class="badge <?= $badgeClass ?> rounded-pill px-3 py-1"><?= $statusText ?></span>
                            </td>
                            <td class="text-end pe-4">
                                <form action="<?= e(admin_url('shifts', ['action' => 'update_status'])) ?>" method="POST" class="d-inline-flex gap-1 align-items-center">
                                    <input type="hidden" name="assignment_id" value="<?= (int)$a['id'] ?>">
                                    <select name="status" class="form-select form-select-sm border-secondary border-opacity-25 shadow-none" onchange="this.form.submit()">
                                        <option value="assigned" <?= $a['status'] === 'assigned' ? 'selected' : '' ?>>Chờ làm</option>
                                        <option value="in_progress" <?= $a['status'] === 'in_progress' ? 'selected' : '' ?>>Đang làm</option>
                                        <option value="completed" <?= $a['status'] === 'completed' ? 'selected' : '' ?>>Hoàn thành</option>
                                        <option value="absent" <?= $a['status'] === 'absent' ? 'selected' : '' ?>>Vắng mặt</option>
                                        <option value="cancelled" <?= $a['status'] === 'cancelled' ? 'selected' : '' ?>>Hủy</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Phân công ca -->
<div class="modal fade" id="assignShiftModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <form action="<?= e(admin_url('shifts', ['action' => 'assign'])) ?>" method="POST">
                <div class="modal-header border-bottom-0 pb-0 px-4 pt-4">
                    <h5 class="modal-title font-serif fw-bold text-forest">Phân công ca làm việc</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4 py-3">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-medium mb-1">Chọn nhân viên <span class="text-danger">*</span></label>
                        <select name="employee_id" class="form-select border-secondary border-opacity-25 shadow-none" required>
                            <option value="">-- Chọn nhân viên --</option>
                            <?php foreach ($staffs as $staff): ?>
                                <option value="<?= (int)$staff['id'] ?>"><?= e($staff['full_name']) ?> (@<?= e($staff['username']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-medium mb-1">Chọn ca làm việc <span class="text-danger">*</span></label>
                        <select name="shift_id" class="form-select border-secondary border-opacity-25 shadow-none" required>
                            <option value="">-- Chọn ca --</option>
                            <?php foreach ($shifts as $sh): ?>
                                <option value="<?= (int)$sh['id'] ?>"><?= e($sh['name']) ?> (<?= substr($sh['start_time'], 0, 5) ?> - <?= substr($sh['end_time'], 0, 5) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-medium mb-1">Ngày làm việc <span class="text-danger">*</span></label>
                        <input type="date" name="work_date" class="form-control border-secondary border-opacity-25 shadow-none" value="<?= date('Y-m-d') ?>" required>
                    </div>
                </div>
                <div class="modal-footer border-top-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-forest rounded-pill px-4">Lưu phân công</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Danh mục ca -->
<div class="modal fade" id="shiftListModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-bottom-0 pb-0 px-4 pt-4">
                <h5 class="modal-title font-serif fw-bold text-forest">Danh mục ca làm việc</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <!-- Thêm ca mới -->
                <form action="<?= e(admin_url('shifts', ['action' => 'store'])) ?>" method="POST" class="row g-2 mb-4 p-3 bg-light rounded-4">
                    <div class="col-md-4">
                        <input type="text" name="name" class="form-control form-control-sm border-0 shadow-none" placeholder="Tên ca (VD: Ca Sáng)" required>
                    </div>
                    <div class="col-md-3">
                        <input type="time" name="start_time" class="form-control form-control-sm border-0 shadow-none" required>
                    </div>
                    <div class="col-md-3">
                        <input type="time" name="end_time" class="form-control form-control-sm border-0 shadow-none" required>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-sm btn-forest w-100"><i class="fa-solid fa-plus me-1"></i> Thêm</button>
                    </div>
                </form>

                <!-- Bảng danh sách ca -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-3 py-2 fw-medium border-0">Tên ca</th>
                                <th class="py-2 fw-medium border-0">Khung giờ</th>
                                <th class="py-2 fw-medium border-0">Trạng thái</th>
                                <th class="text-end pe-3 py-2 fw-medium border-0">Xóa</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            <?php foreach ($shifts as $sh): ?>
                            <tr>
                                <td class="ps-3 fw-bold text-dark"><?= e($sh['name']) ?></td>
                                <td><?= substr($sh['start_time'], 0, 5) ?> - <?= substr($sh['end_time'], 0, 5) ?></td>
                                <td>
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Hoạt động</span>
                                </td>
                                <td class="text-end pe-3">
                                    <a href="<?= e(admin_url('shifts', ['action' => 'delete', 'id' => $sh['id']])) ?>" class="btn btn-sm btn-outline-danger shadow-none" onclick="return confirm('Bạn có chắc chắn muốn xóa ca này?');">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-top-0 px-4 pb-4 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

