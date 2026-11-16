<?php
// admin/pages/shifts/my_shifts.php — Lịch làm việc cá nhân của nhân viên chuẩn gốc VAA THÉ
?>
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="font-serif fw-bold text-forest mb-1">Lịch làm việc của tôi</h4>
        <p class="text-muted small mb-0">Theo dõi các ca làm việc được phân công và trạng thái thực hiện.</p>
    </div>
    <div>
        <a href="<?= e(admin_url('shift_sessions')) ?>" class="btn btn-outline-forest rounded-pill px-3 py-2 small fw-medium">
            <i class="fa-solid fa-cash-register me-1"></i> Lịch sử Mở / Kết ca
        </a>
    </div>
</div>

<?php if ($flashSuccess = get_flash('success')): ?>
<div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
    <i class="fa-solid fa-circle-check me-2"></i> <?= e($flashSuccess) ?>
    <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if ($flashError = get_flash('error')): ?>
<div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
    <i class="fa-solid fa-triangle-exclamation me-2"></i> <?= e($flashError) ?>
    <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted">
                    <tr>
                        <th class="ps-4 py-3 fw-medium border-0">Ngày làm việc</th>
                        <th class="py-3 fw-medium border-0">Ca làm việc</th>
                        <th class="py-3 fw-medium border-0">Thời gian</th>
                        <th class="py-3 fw-medium border-0">Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($myShifts)): ?>
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="fa-regular fa-calendar-xmark fs-2 mb-3 text-secondary opacity-50"></i><br>
                            Hiện tại bạn chưa được phân công ca làm việc nào.
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($myShifts as $s): ?>
                        <tr>
                            <td class="ps-4 fw-medium text-dark">
                                <i class="fa-regular fa-calendar text-caramel me-2"></i>
                                <?= date('d/m/Y', strtotime($s['work_date'])) ?>
                                <?php if ($s['work_date'] === date('Y-m-d')): ?>
                                    <span class="badge bg-forest text-white rounded-pill ms-2 px-2 small">Hôm nay</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="fw-bold text-forest"><?= e($s['shift_name']) ?></span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    <i class="fa-regular fa-clock text-muted me-1"></i>
                                    <?= substr($s['start_time'], 0, 5) ?> - <?= substr($s['end_time'], 0, 5) ?>
                                </span>
                            </td>
                            <td>
                                <?php
                                $statusClass = 'bg-secondary';
                                $statusText = 'Chờ làm';
                                switch ($s['status']) {
                                    case 'assigned':
                                        $statusClass = 'bg-warning text-dark';
                                        $statusText = 'Đã phân công';
                                        break;
                                    case 'in_progress':
                                        $statusClass = 'bg-primary text-white';
                                        $statusText = 'Đang làm việc';
                                        break;
                                    case 'completed':
                                        $statusClass = 'bg-success text-white';
                                        $statusText = 'Hoàn thành';
                                        break;
                                    case 'absent':
                                        $statusClass = 'bg-danger text-white';
                                        $statusText = 'Vắng mặt';
                                        break;
                                    case 'cancelled':
                                        $statusClass = 'bg-dark text-white';
                                        $statusText = 'Đã hủy';
                                        break;
                                }
                                ?>
                                <span class="badge <?= $statusClass ?> rounded-pill px-3 py-2">
                                    <?= $statusText ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
