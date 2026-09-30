<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="font-serif fw-bold text-forest mb-0">Lịch làm việc của tôi</h3>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Ngày làm việc</th>
                            <th>Ca làm việc</th>
                            <th>Thời gian</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($shifts as $s): ?>
                        <tr>
                            <td class="ps-4 fw-medium text-dark">
                                <i class="fa-regular fa-calendar text-caramel me-2"></i>
                                <?= date('d/m/Y', strtotime($s['work_date'])) ?>
                            </td>
                            <td>
                                <span class="fw-bold text-forest"><?= htmlspecialchars($s['shift_name']) ?></span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    <i class="fa-regular fa-clock text-muted me-1"></i>
                                    <?= date('H:i', strtotime($s['start_time'])) ?> - <?= date('H:i', strtotime($s['end_time'])) ?>
                                </span>
                            </td>
                            <td>
                                <?php
                                $statusClass = 'bg-secondary';
                                $statusText = 'Không rõ';
                                switch ($s['status']) {
                                    case 'assigned':
                                        $statusClass = 'bg-primary';
                                        $statusText = 'Đã phân công';
                                        break;
                                    case 'completed':
                                        $statusClass = 'bg-success';
                                        $statusText = 'Hoàn thành';
                                        break;
                                    case 'absent':
                                        $statusClass = 'bg-danger';
                                        $statusText = 'Vắng mặt';
                                        break;
                                    case 'cancelled':
                                        $statusClass = 'bg-dark';
                                        $statusText = 'Đã hủy';
                                        break;
                                }
                                ?>
                                <span class="badge <?= $statusClass ?> bg-opacity-10 text-<?= str_replace('bg-', '', $statusClass) ?> rounded-pill px-3">
                                    <?= $statusText ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php if (empty($shifts)): ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="fa-regular fa-calendar-xmark fs-2 mb-3 text-light"></i><br>
                                Hiện tại bạn chưa được phân công ca làm việc nào.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
