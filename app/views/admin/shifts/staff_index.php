<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="font-serif fw-bold text-dark mb-0">Lịch làm việc của tôi</h4>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Ngày</th>
                        <th>Ca làm việc</th>
                        <th>Thời gian</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($assignments)): ?>
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">Bạn chưa có lịch phân công nào.</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($assignments as $a): ?>
                        <tr>
                            <td class="ps-4 fw-medium"><?= date('d/m/Y', strtotime($a['work_date'])) ?></td>
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
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
