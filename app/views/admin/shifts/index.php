<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="font-serif fw-bold text-dark mb-0">Quản lý ca làm việc</h4>
    <div>
        <button class="btn btn-outline-forest fw-medium px-3 me-2" data-bs-toggle="modal" data-bs-target="#shiftListModal">
            <i class="fa-solid fa-list me-2"></i> Danh mục ca
        </button>
        <button class="btn btn-forest fw-medium px-4" data-bs-toggle="modal" data-bs-target="#assignShiftModal">
            <i class="fa-solid fa-user-plus me-2"></i> Phân công
        </button>
    </div>
</div>

<?php if (isset($_GET['msg'])): ?>
<div class="alert alert-success alert-dismissible fade show">
    Thao tác thành công!
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>
<?php if (isset($_GET['error'])): ?>
<div class="alert alert-danger alert-dismissible fade show">
    Thao tác thất bại. Lỗi: <?= $_GET['error'] === 'duplicate' ? 'Đã phân công nhân viên này vào ca trong ngày!' : 'Vui lòng kiểm tra lại.' ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- Form Filter -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-3">
        <form action="admin.php" method="GET" class="row g-3 align-items-end">
            <input type="hidden" name="route" value="shifts">
            <div class="col-md-3">
                <label class="form-label text-muted small mb-1">Từ ngày</label>
                <input type="date" name="start_date" class="form-control bg-light border-0" value="<?= htmlspecialchars($startDate) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label text-muted small mb-1">Đến ngày</label>
                <input type="date" name="end_date" class="form-control bg-light border-0" value="<?= htmlspecialchars($endDate) ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-dark w-100">Lọc</button>
            </div>
        </form>
    </div>
</div>

<!-- Assignments Table -->
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Ngày</th>
                        <th>Nhân viên</th>
                        <th>Ca làm việc</th>
                        <th>Thời gian</th>
                        <th>Trạng thái</th>
                        <th class="text-end pe-4">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($assignments)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Chưa có dữ liệu phân công</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($assignments as $a): ?>
                        <tr>
                            <td class="ps-4 fw-medium"><?= date('d/m/Y', strtotime($a['work_date'])) ?></td>
                            <td><?= htmlspecialchars($a['employee_name']) ?></td>
                            <td><span class="badge bg-secondary"><?= htmlspecialchars($a['shift_name']) ?></span></td>
                            <td><?= date('H:i', strtotime($a['start_time'])) ?> - <?= date('H:i', strtotime($a['end_time'])) ?></td>
                            <td>
                                <?php
                                $badgeClass = 'bg-secondary';
                                $statusText = 'Assigned';
                                if ($a['status'] === 'assigned') { $badgeClass = 'bg-warning text-dark'; $statusText = 'Chờ làm'; }
                                if ($a['status'] === 'completed') { $badgeClass = 'bg-success'; $statusText = 'Hoàn thành'; }
                                if ($a['status'] === 'absent') { $badgeClass = 'bg-danger'; $statusText = 'Vắng mặt'; }
                                if ($a['status'] === 'cancelled') { $badgeClass = 'bg-dark'; $statusText = 'Đã hủy'; }
                                ?>
                                <span class="badge <?= $badgeClass ?>"><?= $statusText ?></span>
                            </td>
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-light border shadow-none" data-bs-toggle="modal" data-bs-target="#updateStatusModal<?= $a['id'] ?>">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- Modal Update Status -->
                        <div class="modal fade" id="updateStatusModal<?= $a['id'] ?>" tabindex="-1">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content border-0 rounded-4 shadow">
                                    <form action="admin.php?route=shifts&action=update_status" method="POST">
                                        <div class="modal-header border-bottom-0 pb-0">
                                            <h5 class="modal-title fw-bold">Cập nhật trạng thái</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="assignment_id" value="<?= $a['id'] ?>">
                                            <p class="mb-3 text-muted small">Ca: <?= $a['shift_name'] ?> - <?= date('d/m/Y', strtotime($a['work_date'])) ?><br>Nhân viên: <?= $a['employee_name'] ?></p>
                                            <select name="status" class="form-select bg-light border-0">
                                                <option value="assigned" <?= $a['status'] === 'assigned' ? 'selected' : '' ?>>Chờ làm (Assigned)</option>
                                                <option value="completed" <?= $a['status'] === 'completed' ? 'selected' : '' ?>>Hoàn thành</option>
                                                <option value="absent" <?= $a['status'] === 'absent' ? 'selected' : '' ?>>Vắng mặt</option>
                                                <option value="cancelled" <?= $a['status'] === 'cancelled' ? 'selected' : '' ?>>Đã hủy</option>
                                            </select>
                                        </div>
                                        <div class="modal-footer border-top-0 pt-0">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                                            <button type="submit" class="btn btn-forest">Cập nhật</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Assign Shift -->
<div class="modal fade" id="assignShiftModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 rounded-4 shadow">
            <form action="admin.php?route=shifts&action=assign" method="POST">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Phân công ca làm việc</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-medium">Ngày làm việc</label>
                        <input type="date" name="work_date" class="form-control bg-light border-0" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-medium">Nhân viên</label>
                        <select name="employee_id" class="form-select bg-light border-0" required>
                            <option value="">-- Chọn nhân viên --</option>
                            <?php foreach ($staffs as $s): ?>
                                <?php if ($s['status'] == 1): ?>
                                <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['full_name']) ?> (<?= $s['username'] ?>)</option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-medium">Ca làm việc</label>
                        <select name="shift_id" class="form-select bg-light border-0" required>
                            <option value="">-- Chọn ca --</option>
                            <?php foreach ($shifts as $sh): ?>
                                <?php if ($sh['status'] == 1): ?>
                                <option value="<?= $sh['id'] ?>"><?= htmlspecialchars($sh['name']) ?> (<?= date('H:i', strtotime($sh['start_time'])) ?> - <?= date('H:i', strtotime($sh['end_time'])) ?>)</option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-forest">Phân công</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Shift Categories -->
<div class="modal fade" id="shiftListModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-bottom pb-3">
                <h5 class="modal-title fw-bold">Danh mục ca làm việc</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="p-3 bg-light border-bottom">
                    <form action="admin.php?route=shifts&action=store" method="POST" class="row g-2 align-items-end">
                        <div class="col-md-4">
                            <input type="text" name="name" class="form-control border-0" placeholder="Tên ca (VD: Ca Sáng)" required>
                        </div>
                        <div class="col-md-3">
                            <input type="time" name="start_time" class="form-control border-0" required>
                        </div>
                        <div class="col-md-3">
                            <input type="time" name="end_time" class="form-control border-0" required>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-dark w-100">Thêm</button>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Tên ca</th>
                                <th>Thời gian</th>
                                <th>Trạng thái</th>
                                <th class="text-end pe-4">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($shifts as $sh): ?>
                            <tr>
                                <td class="ps-4 fw-medium"><?= htmlspecialchars($sh['name']) ?></td>
                                <td><?= date('H:i', strtotime($sh['start_time'])) ?> - <?= date('H:i', strtotime($sh['end_time'])) ?></td>
                                <td>
                                    <?php if($sh['status'] == 1): ?>
                                        <span class="badge bg-success">Đang hoạt động</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Tạm ngưng</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="admin.php?route=shifts&action=delete&id=<?= $sh['id'] ?>" class="btn btn-sm btn-outline-danger shadow-none" onclick="return confirm('Bạn có chắc chắn muốn xóa ca này?');">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
